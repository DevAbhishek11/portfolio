<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    public function index()
    {
        $services = [
            [
                'icon'  => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4',
                'title' => 'Full Stack Web Development',
                'desc'  => 'End-to-end web applications from architecture to deployment. I handle both the client-facing interface and the server-side logic.',
                'stack' => ['Laravel', 'React', 'MySQL', 'Docker'],
                'color' => '#8b5cf6',
            ],
            [
                'icon'  => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                'title' => 'Frontend Development',
                'desc'  => 'Pixel-perfect, performant, and accessible user interfaces. Specialising in React, Next.js, and modern CSS frameworks.',
                'stack' => ['React', 'Next.js', 'Tailwind CSS', 'TypeScript'],
                'color' => '#06b6d4',
            ],
            [
                'icon'  => 'M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2',
                'title' => 'Backend Development',
                'desc'  => 'Robust, secure, and scalable server-side systems. REST APIs, microservices, and database architecture.',
                'stack' => ['PHP', 'Laravel', 'Node.js', 'PostgreSQL'],
                'color' => '#f43f5e',
            ],
            [
                'icon'  => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
                'title' => 'Mobile App Development',
                'desc'  => 'Cross-platform mobile applications that feel native on both iOS and Android using React Native.',
                'stack' => ['React Native', 'Expo', 'Firebase'],
                'color' => '#eab308',
            ],
            [
                'icon'  => 'M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18',
                'title' => 'Database Design',
                'desc'  => 'Efficient schema design, query optimisation, and data architecture for both relational and NoSQL databases.',
                'stack' => ['MySQL', 'PostgreSQL', 'MongoDB', 'Redis'],
                'color' => '#22c55e',
            ],
            [
                'icon'  => 'M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                'title' => 'API Development',
                'desc'  => 'Clean, documented, and versioned REST or GraphQL APIs built for performance and developer experience.',
                'stack' => ['REST', 'GraphQL', 'Swagger', 'Postman'],
                'color' => '#a78bfa',
            ],
        ];

        $process = [
            ['step' => '01', 'title' => 'Discovery',    'desc' => 'Understanding your goals, users, and technical requirements.'],
            ['step' => '02', 'title' => 'Planning',     'desc' => 'Architecture design, tech stack selection, and project roadmap.'],
            ['step' => '03', 'title' => 'Development',  'desc' => 'Iterative builds with regular check-ins and progress demos.'],
            ['step' => '04', 'title' => 'Testing',      'desc' => 'Thorough QA across devices, browsers, and edge cases.'],
            ['step' => '05', 'title' => 'Launch',       'desc' => 'Smooth deployment with monitoring and rollback plans.'],
            ['step' => '06', 'title' => 'Support',      'desc' => 'Ongoing maintenance, updates, and feature additions.'],
        ];

        $stats = [
            ['value' => '50+',  'label' => 'Projects Completed'],
            ['value' => '30+',  'label' => 'Happy Clients'],
            ['value' => '5+',   'label' => 'Years Experience'],
            ['value' => '15+',  'label' => 'Technologies'],
        ];

        return view('frontend.services', compact('services', 'process', 'stats'));
    }
}
