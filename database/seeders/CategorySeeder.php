<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Information Technology',
                'description' => 'Software development, web, mobile, networks, and IT support.',
                'icon'        => 'fa-laptop-code',
                'skills'      => [
                    'Web Development', 'Mobile App Development', 'PHP', 'Laravel', 'React',
                    'Vue.js', 'Node.js', 'Python', 'SQL & Database', 'Network Administration',
                    'UI/UX Design', 'WordPress', 'DevOps', 'Cybersecurity', 'Data Analysis',
                ],
            ],
            [
                'name'        => 'Translation & Language',
                'description' => 'Dzongkha, English, Hindi and other language translation services.',
                'icon'        => 'fa-language',
                'skills'      => [
                    'Dzongkha Translation', 'English-Dzongkha', 'Hindi Translation',
                    'Document Translation', 'Subtitles & Captioning', 'Interpretation',
                    'Localization', 'Transcription',
                ],
            ],
            [
                'name'        => 'Design & Creative',
                'description' => 'Graphic design, photography, video production, and arts.',
                'icon'        => 'fa-paint-brush',
                'skills'      => [
                    'Graphic Design', 'Logo Design', 'Photography', 'Video Editing',
                    'Illustration', 'Traditional Art', 'Thangka Painting', 'Branding',
                    'Print Design', 'Animation',
                ],
            ],
            [
                'name'        => 'Writing & Content',
                'description' => 'Copywriting, blogging, technical writing, and editing.',
                'icon'        => 'fa-pen-nib',
                'skills'      => [
                    'Copywriting', 'Blog Writing', 'Technical Writing', 'Proofreading',
                    'Script Writing', 'SEO Writing', 'Social Media Content', 'Research Writing',
                ],
            ],
            [
                'name'        => 'Engineering & Architecture',
                'description' => 'Civil drafting, structural design, electrical, and mechanical services.',
                'icon'        => 'fa-drafting-compass',
                'skills'      => [
                    'AutoCAD Drafting', 'Civil Engineering', 'Structural Design',
                    'Architectural Drawing', 'Quantity Surveying', 'Electrical Engineering',
                    'Mechanical Engineering', 'Land Surveying', 'GIS Mapping',
                ],
            ],
            [
                'name'        => 'Tourism & Hospitality',
                'description' => 'Trekking guides, tour packages, hotel management, and tourism services.',
                'icon'        => 'fa-mountain',
                'skills'      => [
                    'Trekking Guide', 'Tour Guiding', 'Hotel Management', 'Event Planning',
                    'Cultural Tourism', 'Festival Planning', 'Travel Photography',
                ],
            ],
            [
                'name'        => 'Agriculture & Environment',
                'description' => 'Organic farming consultation, agri-tech, and environmental assessment.',
                'icon'        => 'fa-seedling',
                'skills'      => [
                    'Organic Farming', 'Agricultural Consulting', 'Permaculture', 'Apiculture',
                    'Environmental Assessment', 'Forest Management', 'GHG Assessment',
                    'Irrigation Planning',
                ],
            ],
            [
                'name'        => 'Business & Finance',
                'description' => 'Accounting, business planning, legal, and financial advisory.',
                'icon'        => 'fa-briefcase',
                'skills'      => [
                    'Accounting', 'Tax Filing', 'Business Planning', 'Financial Analysis',
                    'Company Registration', 'Audit', 'Bookkeeping', 'Investment Advisory',
                ],
            ],
            [
                'name'        => 'Education & Training',
                'description' => 'Tutoring, online courses, training programs, and coaching.',
                'icon'        => 'fa-graduation-cap',
                'skills'      => [
                    'Mathematics Tutoring', 'English Teaching', 'Science Tutoring',
                    'Online Course Creation', 'Corporate Training', 'Coaching',
                    'Research & Academic Writing',
                ],
            ],
            [
                'name'        => 'Health & Wellness',
                'description' => 'Fitness coaching, nutrition, mental health, and wellness services.',
                'icon'        => 'fa-heartbeat',
                'skills'      => [
                    'Fitness Coaching', 'Yoga Instruction', 'Nutrition Planning',
                    'Mental Health Counseling', 'Traditional Medicine Consulting',
                ],
            ],
        ];

        foreach ($categories as $catData) {
            $skillNames = $catData['skills'] ?? [];
            unset($catData['skills']);

            $category = Category::firstOrCreate(
                ['slug' => Str::slug($catData['name'])],
                array_merge($catData, ['is_active' => true])
            );

            foreach ($skillNames as $skillName) {
                Skill::firstOrCreate(
                    ['name' => $skillName],
                    [
                        'category_id' => $category->id,
                        'is_active'   => true,
                    ]
                );
            }
        }

        $this->command->info('Categories and skills seeded successfully.');
    }
}
