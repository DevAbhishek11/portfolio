<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('is_admin', true)->first();
        if (! $user) return;

        $skills = [
            // Frontend
            ['name' => 'React / Next.js',  'category' => 'Frontend',  'level' => 92, 'color' => '#06b6d4', 'sort_order' => 1],
            ['name' => 'HTML / CSS',        'category' => 'Frontend',  'level' => 96, 'color' => '#f43f5e', 'sort_order' => 2],
            ['name' => 'TypeScript',        'category' => 'Frontend',  'level' => 85, 'color' => '#3178c6', 'sort_order' => 3],
            ['name' => 'Tailwind CSS',      'category' => 'Frontend',  'level' => 94, 'color' => '#38bdf8', 'sort_order' => 4],

            // Backend
            ['name' => 'Laravel / PHP',     'category' => 'Backend',   'level' => 95, 'color' => '#8b5cf6', 'sort_order' => 1],
            ['name' => 'Node.js',           'category' => 'Backend',   'level' => 82, 'color' => '#4ade80', 'sort_order' => 2],
            ['name' => 'REST APIs',         'category' => 'Backend',   'level' => 90, 'color' => '#a78bfa', 'sort_order' => 3],
            ['name' => 'GraphQL',           'category' => 'Backend',   'level' => 72, 'color' => '#e879f9', 'sort_order' => 4],

            // Database
            ['name' => 'MySQL',             'category' => 'Database',  'level' => 90, 'color' => '#f97316', 'sort_order' => 1],
            ['name' => 'PostgreSQL',        'category' => 'Database',  'level' => 82, 'color' => '#60a5fa', 'sort_order' => 2],
            ['name' => 'MongoDB',           'category' => 'Database',  'level' => 75, 'color' => '#4ade80', 'sort_order' => 3],
            ['name' => 'Redis',             'category' => 'Database',  'level' => 70, 'color' => '#f43f5e', 'sort_order' => 4],

            // Mobile
            ['name' => 'React Native',      'category' => 'Mobile',    'level' => 78, 'color' => '#06b6d4', 'sort_order' => 1],
            ['name' => 'Electron',          'category' => 'Mobile',    'level' => 65, 'color' => '#a78bfa', 'sort_order' => 2],

            // Tools
            ['name' => 'Git / GitHub',      'category' => 'Tools',     'level' => 92, 'color' => '#f97316', 'sort_order' => 1],
            ['name' => 'Docker',            'category' => 'Tools',     'level' => 75, 'color' => '#60a5fa', 'sort_order' => 2],
            ['name' => 'Linux / Bash',      'category' => 'Tools',     'level' => 80, 'color' => '#facc15', 'sort_order' => 3],
            ['name' => 'Figma',             'category' => 'Tools',     'level' => 70, 'color' => '#f43f5e', 'sort_order' => 4],
        ];

        // Mark first 8 as radar featured
        foreach ($skills as $i => &$skill) {
            $skill['user_id']     = $user->id;
            $skill['is_featured'] = $i < 8;
        }

        Skill::insert($skills);
        $this->command->info('Skills seeded: ' . count($skills) . ' skills.');
    }
}
