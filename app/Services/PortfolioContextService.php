<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Support\Str;

/**
 * Builds the AI chat widget's grounding context straight from the live
 * database (projects, skills, blog posts, owner bio) so the assistant stays
 * accurate as content is added or edited in the admin — nothing here is
 * hand-maintained or duplicated.
 */
class PortfolioContextService
{
    public function systemPrompt(): string
    {
        $owner = portfolio_owner();
        $name = $owner?->name ?? config('portfolio.site_name');
        $title = $owner?->title ?? config('portfolio.site_tagline');
        $bio = $owner?->bio ?? config('portfolio.meta.description');
        $location = $owner?->location ?? config('portfolio.site_location');

        return <<<PROMPT
            You are the AI assistant embedded on {$name}'s portfolio website. You represent {$name} to visitors — recruiters, potential clients, and fellow developers.

            {$name} — {$title}{$this->when($location, " · {$location}")}
            Bio: {$bio}

            Rules:
            - A "PORTFOLIO CONTEXT" block is included in this same conversation, right before the visitor's message. It IS available to you right now — never say you don't have access to it or that the portfolio "doesn't specify" something without actually checking that block first. Read it and answer from it directly and confidently.
            - Answer using ONLY that context — it is pulled fresh from the live database on every request, so treat it as current and complete for what it covers.
            - Never invent projects, clients, employers, testimonials, years of experience, or metrics that are not present in that context. Only if something is genuinely absent from it, say so plainly and offer to have {$name} follow up directly — don't use this as a generic hedge.
            - If asked something unrelated to {$name} or the portfolio (general coding help, unrelated trivia, etc.), you may answer briefly and helpfully, but bring it back to how {$name} could help.
            - If the visitor expresses interest in hiring, collaborating, or getting in touch — or shares their name/email/project details — offer to pass a message to {$name}, and ask for their name, email, and a short summary of what they need if they haven't already given it.
            - Keep replies concise (2-4 sentences unless the visitor asks for more detail), friendly, and professional. No markdown headers or bullet spam — write like a helpful person, not a brochure.
            PROMPT;
    }

    /**
     * Returns a compact, relevance-scoped slice of portfolio data as plain
     * text to include alongside the visitor's message — not the entire DB
     * on every turn, to keep token usage and free-tier rate limits sane.
     */
    public function scopedContext(string $userMessage): string
    {
        $needle = Str::lower($userMessage);

        $skills = Skill::orderBy('category')->orderBy('sort_order')->get(['name', 'category', 'level']);
        $projects = Project::published()->with('techStacks:id,project_id,name')
            ->orderByDesc('is_featured')->orderByDesc('created_at')
            ->get(['id', 'title', 'short_description', 'description', 'category', 'github_url', 'live_url', 'is_featured']);
        $blogs = Blog::published()->latest('published_at')
            ->limit(15)->get(['title', 'excerpt', 'category', 'tags']);

        $matchedProjects = $projects->filter(fn ($p) => $this->matches($needle, [
            $p->title, $p->category, ...$p->techStacks->pluck('name')->all(),
        ]));
        $matchedBlogs = $blogs->filter(fn ($b) => $this->matches($needle, [
            $b->title, $b->category, ...($b->tags ?? []),
        ]));

        // Always include a handful of featured/recent items so the model has
        // something to talk about even when nothing matched the keywords.
        $projectsToShow = $matchedProjects->isNotEmpty()
            ? $matchedProjects->take(6)
            : $projects->take(5);
        $blogsToShow = $matchedBlogs->isNotEmpty()
            ? $matchedBlogs->take(5)
            : $blogs->take(4);

        $lines = [];

        if ($skills->isNotEmpty()) {
            $lines[] = 'SKILLS:';
            foreach ($skills->groupBy('category') as $category => $group) {
                $lines[] = "- {$category}: " . $group->map(fn ($s) => "{$s->name} ({$s->level}%)")->implode(', ');
            }
        }

        if ($projectsToShow->isNotEmpty()) {
            $lines[] = "\nPROJECTS:";
            foreach ($projectsToShow as $p) {
                $tech = $p->techStacks->pluck('name')->implode(', ');
                $desc = $p->short_description ?: Str::limit(strip_tags($p->description ?? ''), 200);
                $lines[] = "- {$p->title} ({$p->category}){$this->when($p->is_featured, ' [featured]')}: {$desc}"
                    . ($tech ? " Tech: {$tech}." : '')
                    . ($p->live_url ? " Live: {$p->live_url}." : '')
                    . ($p->github_url ? " Code: {$p->github_url}." : '');
            }
        }

        if ($blogsToShow->isNotEmpty()) {
            $lines[] = "\nBLOG POSTS:";
            foreach ($blogsToShow as $b) {
                $lines[] = "- \"{$b->title}\" ({$b->category}): " . Str::limit(strip_tags($b->excerpt ?? ''), 160);
            }
        }

        return trim(implode("\n", $lines)) ?: 'No portfolio content is available yet.';
    }

    private function matches(string $needle, array $haystackParts): bool
    {
        foreach (array_filter($haystackParts) as $part) {
            $part = Str::lower((string) $part);
            if ($part !== '' && (str_contains($needle, $part) || str_contains($part, $needle))) {
                return true;
            }
            // Also match individual significant words (avoid matching tiny/common words).
            foreach (preg_split('/[\s,]+/', $part) as $word) {
                if (strlen($word) >= 4 && str_contains($needle, $word)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function when(mixed $condition, string $text): string
    {
        return $condition ? $text : '';
    }
}
