<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactQueries as ContactQuery;
use App\Models\ContactReplies as ContactReply;
use App\Models\User;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactQueryController extends Controller
{
    public function __construct(private MailService $mailService) {}

    public function index(Request $request)
    {
        $query = ContactQuery::latest();

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
                    ->orWhere('subject', 'like', "%{$s}%");
            });
        }

        $contacts     = $query->paginate(20)->withQueryString();
        $unreadCount  = ContactQuery::where('status', 'unread')->count();

        if ($request->ajax() || $request->boolean('ajax')) {
            return response()->json([
                'html' => view('admin.contacts._table', compact('contacts', 'unreadCount'))->render(),
            ]);
        }

        return view('admin.contacts.index', compact('contacts', 'unreadCount'));
    }

    public function ajaxList(Request $request)
    {
        $request->merge(['ajax' => true]);
        return $this->index($request);
    }

    public function show(int $id)
    {
        $contact = ContactQuery::with('replies.user')->findOrFail($id);

        if ($contact->status === 'unread') {
            $contact->update(['status' => 'read']);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    public function reply(Request $request, int $id)
    {
        $contact = ContactQuery::findOrFail($id);

        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        $reply = ContactReply::create([
            'contact_query_id' => $contact->id,
            'user_id'          => session('admin_user_id'),
            'subject'          => $data['subject'],
            'message'          => $data['message'],
            'sent_at'          => now(),
        ]);

        $contact->update(['status' => 'replied']);

        try {
            $this->mailService->sendContactReply($contact, $reply);
        } catch (\Throwable $e) {
            Log::error('Contact reply email failed', [
                'query_id' => $contact->id,
                'reply_id' => $reply->id,
                'error'    => $e->getMessage(),
            ]);
            return redirect()->route('admin.contacts.show', $id)
                ->with('error', 'Reply saved, but the email could not be sent. Check mail configuration.');
        }

        return redirect()->route('admin.contacts.show', $id)
            ->with('success', 'Reply sent successfully.');
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate(['status' => 'required|in:unread,read,replied,archived']);
        ContactQuery::findOrFail($id)->update(['status' => $request->status]);
        return back()->with('success', 'Status updated.');
    }

    public function destroy(int $id)
    {
        ContactQuery::findOrFail($id)->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Query deleted.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:mark_read,archive,delete',
            'ids'    => 'required|array',
            'ids.*'  => 'integer|exists:contact_queries,id',
        ]);

        $query = ContactQuery::whereIn('id', $request->ids);

        match ($request->action) {
            'mark_read' => $query->update(['status' => 'read']),
            'archive'   => $query->update(['status' => 'archived']),
            'delete'    => $query->delete(),
        };

        return back()->with('success', 'Bulk action applied.');
    }
}
