<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Contract;
use App\Models\Job;
use App\Models\Milestone;
use App\Models\Profile;
use App\Models\Proposal;
use App\Models\ProposalMilestone;
use App\Models\Review;
use App\Models\Skill;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BhutanDemoSeeder extends Seeder
{
    public function run(): void
    {
        // ═══════════════════════════════════════════════════════
        //  1. FREELANCERS
        // ═══════════════════════════════════════════════════════
        $freelancers = [
            [
                'name'     => 'Kinley Dorji',
                'email'    => 'kinley.dorji@demo.bt',
                'phone'    => '+97517234567',
                'role'     => 'freelancer',
                'profile'  => [
                    'headline'         => 'Full-Stack Web Developer | Laravel & React Specialist',
                    'bio'              => 'I am a passionate full-stack developer from Thimphu with 5 years of experience building web applications for Bhutanese businesses and government agencies. Proficient in Laravel, React, Vue.js, and MySQL. I have delivered e-government portals, e-commerce platforms, and custom ERP systems.',
                    'dzongkhag'        => 'Thimphu',
                    'gewog'            => 'Kawang',
                    'hourly_rate'      => 1200,
                    'experience_years' => 5,
                    'availability'     => 'available',
                    'average_rating'   => 4.8,
                    'total_reviews'    => 14,
                    'total_jobs_completed' => 12,
                    'total_earned'     => 145000,
                ],
                'wallet_balance' => 28500,
                'skills' => ['Web Development', 'Laravel', 'React', 'Vue.js', 'SQL & Database'],
            ],
            [
                'name'     => 'Tshering Wangmo',
                'email'    => 'tshering.wangmo@demo.bt',
                'phone'    => '+97517345678',
                'role'     => 'freelancer',
                'profile'  => [
                    'headline'         => 'Creative Graphic Designer | Thangka-Inspired Branding',
                    'bio'              => 'I blend traditional Bhutanese arts with modern design thinking. Based in Paro, I specialize in logos, branding, and print design that reflect Bhutanese culture. Clients include hotels, government agencies, and NGOs across the country.',
                    'dzongkhag'        => 'Paro',
                    'gewog'            => 'Dopshari',
                    'hourly_rate'      => 800,
                    'experience_years' => 4,
                    'availability'     => 'available',
                    'average_rating'   => 4.9,
                    'total_reviews'    => 22,
                    'total_jobs_completed' => 20,
                    'total_earned'     => 210000,
                ],
                'wallet_balance' => 42000,
                'skills' => ['Graphic Design', 'Logo Design', 'Branding', 'Thangka Painting', 'Illustration'],
            ],
            [
                'name'     => 'Sonam Choden',
                'email'    => 'sonam.choden@demo.bt',
                'phone'    => '+97517456789',
                'role'     => 'freelancer',
                'profile'  => [
                    'headline'         => 'Certified Dzongkha Translator & Interpreter',
                    'bio'              => 'Native Dzongkha speaker with 7 years of professional translation experience. I have translated policy documents for the Royal Government of Bhutan, NGO reports, and educational materials. Specialize in English-Dzongkha and Hindi-Dzongkha translation.',
                    'dzongkhag'        => 'Bumthang',
                    'gewog'            => 'Chhoekhor',
                    'hourly_rate'      => 600,
                    'experience_years' => 7,
                    'availability'     => 'available',
                    'average_rating'   => 5.0,
                    'total_reviews'    => 31,
                    'total_jobs_completed' => 29,
                    'total_earned'     => 318000,
                ],
                'wallet_balance' => 55000,
                'skills' => ['Dzongkha Translation', 'English-Dzongkha', 'Document Translation', 'Interpretation', 'Transcription'],
            ],
            [
                'name'     => 'Ugyen Dorji',
                'email'    => 'ugyen.dorji@demo.bt',
                'phone'    => '+97517567890',
                'role'     => 'freelancer',
                'profile'  => [
                    'headline'         => 'Civil Engineer & AutoCAD Drafting Expert',
                    'bio'              => 'Licensed civil engineer with a decade of experience in structural design, AutoCAD drafting, and quantity surveying for construction projects across Bhutan. Worked on residential, commercial, and hydropower infrastructure projects.',
                    'dzongkhag'        => 'Punakha',
                    'gewog'            => 'Punakha',
                    'hourly_rate'      => 1500,
                    'experience_years' => 10,
                    'availability'     => 'busy',
                    'average_rating'   => 4.7,
                    'total_reviews'    => 9,
                    'total_jobs_completed' => 8,
                    'total_earned'     => 380000,
                ],
                'wallet_balance' => 62000,
                'skills' => ['AutoCAD Drafting', 'Civil Engineering', 'Structural Design', 'Quantity Surveying', 'Architectural Drawing'],
            ],
            [
                'name'     => 'Pema Lhamo',
                'email'    => 'pema.lhamo@demo.bt',
                'phone'    => '+97517678901',
                'role'     => 'freelancer',
                'profile'  => [
                    'headline'         => 'SEO Content Writer | Tourism & Culture Niche',
                    'bio'              => 'Freelance writer and blogger from Thimphu specializing in travel, culture, and sustainable development content. I write for tourism companies, NGOs, and digital publications. Fluent in English, Dzongkha, and Hindi.',
                    'dzongkhag'        => 'Thimphu',
                    'gewog'            => 'Medrogom',
                    'hourly_rate'      => 500,
                    'experience_years' => 3,
                    'availability'     => 'available',
                    'average_rating'   => 4.6,
                    'total_reviews'    => 18,
                    'total_jobs_completed' => 17,
                    'total_earned'     => 89000,
                ],
                'wallet_balance' => 14000,
                'skills' => ['SEO Writing', 'Blog Writing', 'Copywriting', 'Social Media Content', 'Research Writing'],
            ],
            [
                'name'     => 'Karma Tenzin',
                'email'    => 'karma.tenzin@demo.bt',
                'phone'    => '+97517789012',
                'role'     => 'freelancer',
                'profile'  => [
                    'headline'         => 'Licensed Trekking Guide | Snowman Trek & Druk Path Specialist',
                    'bio'              => 'Experienced high-altitude trekking guide from Paro with 12 years leading domestic and international trekkers on the Snowman Trek, Druk Path, and Jhomolhari routes. Expert in safety protocols, first aid, and cultural interpretation.',
                    'dzongkhag'        => 'Paro',
                    'gewog'            => 'Tsento',
                    'hourly_rate'      => 2000,
                    'experience_years' => 12,
                    'availability'     => 'available',
                    'average_rating'   => 4.9,
                    'total_reviews'    => 35,
                    'total_jobs_completed' => 33,
                    'total_earned'     => 520000,
                ],
                'wallet_balance' => 85000,
                'skills' => ['Trekking Guide', 'Tour Guiding', 'Travel Photography', 'Cultural Tourism'],
            ],
            [
                'name'     => 'Dechen Yangzom',
                'email'    => 'dechen.yangzom@demo.bt',
                'phone'    => '+97517890123',
                'role'     => 'freelancer',
                'profile'  => [
                    'headline'         => 'Mobile App Developer | Android & iOS (Flutter)',
                    'bio'              => 'Flutter and Dart developer delivering cross-platform apps for Bhutanese startups and businesses. Developed apps for e-commerce, event management, and public service delivery. Based in Thimphu with remote-friendly work style.',
                    'dzongkhag'        => 'Thimphu',
                    'gewog'            => 'Kawang',
                    'hourly_rate'      => 1400,
                    'experience_years' => 4,
                    'availability'     => 'available',
                    'average_rating'   => 4.7,
                    'total_reviews'    => 11,
                    'total_jobs_completed' => 10,
                    'total_earned'     => 178000,
                ],
                'wallet_balance' => 31000,
                'skills' => ['Mobile App Development', 'UI/UX Design', 'Python', 'DevOps'],
            ],
            [
                'name'     => 'Namgay Tshering',
                'email'    => 'namgay.tshering@demo.bt',
                'phone'    => '+97517901234',
                'role'     => 'freelancer',
                'profile'  => [
                    'headline'         => 'Professional Photographer & Videographer',
                    'bio'              => 'Award-winning landscape and cultural photographer based in Wangdue Phodrang. Published works in National Geographic Bhutan and leading tourism brochures. Expert in drone photography, event coverage, and post-production editing.',
                    'dzongkhag'        => 'Wangdue Phodrang',
                    'gewog'            => 'Nyisho',
                    'hourly_rate'      => 1100,
                    'experience_years' => 6,
                    'availability'     => 'available',
                    'average_rating'   => 4.8,
                    'total_reviews'    => 25,
                    'total_jobs_completed' => 24,
                    'total_earned'     => 285000,
                ],
                'wallet_balance' => 47000,
                'skills' => ['Photography', 'Video Editing', 'Travel Photography', 'Branding'],
            ],
        ];

        // ═══════════════════════════════════════════════════════
        //  2. JOB POSTERS
        // ═══════════════════════════════════════════════════════
        $posters = [
            [
                'name'     => 'Tenzin Norbu',
                'email'    => 'tenzin.norbu@btc.bt',
                'phone'    => '+97517112233',
                'role'     => 'job_poster',
                'profile'  => [
                    'bio'          => 'Procurement Manager at Bhutan Tourism Council. We hire freelancers for digital marketing, content creation, and web development projects.',
                    'dzongkhag'    => 'Thimphu',
                    'company_name' => 'Bhutan Tourism Council',
                    'industry'     => 'Tourism & Hospitality',
                    'company_size' => '100-500',
                    'total_spent'  => 450000,
                ],
                'wallet_balance' => 75000,
            ],
            [
                'name'     => 'Chimi Dema',
                'email'    => 'chimi.dema@dhi.bt',
                'phone'    => '+97517223344',
                'role'     => 'job_poster',
                'profile'  => [
                    'bio'          => 'IT Manager at Druk Holding & Investments. We outsource software development, data analytics, and cybersecurity consulting to qualified freelancers.',
                    'dzongkhag'    => 'Thimphu',
                    'company_name' => 'Druk Holding & Investments',
                    'industry'     => 'Finance & Investment',
                    'company_size' => '500+',
                    'total_spent'  => 780000,
                ],
                'wallet_balance' => 120000,
            ],
            [
                'name'     => 'Dorji Wangchuk',
                'email'    => 'dorji.wangchuk@bbs.bt',
                'phone'    => '+97517334455',
                'role'     => 'job_poster',
                'profile'  => [
                    'bio'          => 'Content Director at Bhutan Broadcasting Service. We hire videographers, writers, translators, and animators for our productions.',
                    'dzongkhag'    => 'Thimphu',
                    'company_name' => 'Bhutan Broadcasting Service',
                    'industry'     => 'Media & Broadcasting',
                    'company_size' => '100-500',
                    'total_spent'  => 325000,
                ],
                'wallet_balance' => 50000,
            ],
            [
                'name'     => 'Sangay Lhamo',
                'email'    => 'sangay.lhamo@rcb.bt',
                'phone'    => '+97517445566',
                'role'     => 'job_poster',
                'profile'  => [
                    'bio'          => 'Digital Transformation Lead at Royal Insurance Corporation of Bhutan. We develop digital insurance products and need tech and design talent.',
                    'dzongkhag'    => 'Thimphu',
                    'company_name' => 'Royal Insurance Corporation of Bhutan',
                    'industry'     => 'Insurance & Finance',
                    'company_size' => '100-500',
                    'total_spent'  => 190000,
                ],
                'wallet_balance' => 40000,
            ],
            [
                'name'     => 'Rinzin Peldon',
                'email'    => 'rinzin.peldon@medicorp.bt',
                'phone'    => '+97517556677',
                'role'     => 'job_poster',
                'profile'  => [
                    'bio'          => 'Operations Director at Medicorp Bhutan. We hire freelancers for medical content writing, app development, and facility design.',
                    'dzongkhag'    => 'Thimphu',
                    'company_name' => 'Medicorp Bhutan Pvt. Ltd.',
                    'industry'     => 'Healthcare',
                    'company_size' => '51-100',
                    'total_spent'  => 95000,
                ],
                'wallet_balance' => 25000,
            ],
            [
                'name'     => 'Jigme Tsheltrim',
                'email'    => 'jigme.tsheltrim@drukair.bt',
                'phone'    => '+97517667788',
                'role'     => 'job_poster',
                'profile'  => [
                    'bio'          => 'Marketing Manager at Druk Air Corporation. We commission photography, video production, and brand design for our airline marketing campaigns.',
                    'dzongkhag'    => 'Paro',
                    'company_name' => 'Druk Air Corporation',
                    'industry'     => 'Aviation & Transport',
                    'company_size' => '100-500',
                    'total_spent'  => 260000,
                ],
                'wallet_balance' => 60000,
            ],
        ];

        // ═══════════════════════════════════════════════════════
        //  3. CREATE USERS
        // ═══════════════════════════════════════════════════════
        $createdFreelancers = [];
        foreach ($freelancers as $data) {
            $user = User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'phone'             => $data['phone'],
                'password'          => Hash::make('Demo@1234'),
                'role'              => $data['role'],
                'email_verified_at' => now(),
            ]);
            $user->assignRole($data['role']);

            // Profile
            $p = $data['profile'];
            Profile::where('user_id', $user->id)->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'headline'             => $p['headline'],
                    'bio'                  => $p['bio'],
                    'dzongkhag'            => $p['dzongkhag'],
                    'gewog'                => $p['gewog'],
                    'hourly_rate'          => $p['hourly_rate'],
                    'experience_years'     => $p['experience_years'],
                    'availability'         => $p['availability'],
                    'average_rating'       => $p['average_rating'],
                    'total_reviews'        => $p['total_reviews'],
                    'total_jobs_completed' => $p['total_jobs_completed'],
                    'total_earned'         => $p['total_earned'],
                ]
            );

            // Wallet
            Wallet::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'available_balance' => $data['wallet_balance'],
                    'escrow_balance'    => 0,
                    'total_earned'      => $p['total_earned'],
                    'total_spent'       => 0,
                ]
            );

            $createdFreelancers[$data['email']] = $user;
        }

        $createdPosters = [];
        foreach ($posters as $data) {
            $user = User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'phone'             => $data['phone'],
                'password'          => Hash::make('Demo@1234'),
                'role'              => $data['role'],
                'email_verified_at' => now(),
            ]);
            $user->assignRole($data['role']);

            $p = $data['profile'];
            Profile::where('user_id', $user->id)->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'bio'          => $p['bio'],
                    'dzongkhag'    => $p['dzongkhag'],
                    'company_name' => $p['company_name'],
                    'industry'     => $p['industry'],
                    'company_size' => $p['company_size'],
                    'total_spent'  => $p['total_spent'],
                ]
            );

            Wallet::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'available_balance' => $data['wallet_balance'],
                    'escrow_balance'    => 12000,
                    'total_earned'      => 0,
                    'total_spent'       => $p['total_spent'],
                ]
            );

            $createdPosters[$data['email']] = $user;
        }

        // ═══════════════════════════════════════════════════════
        //  4. ASSIGN SKILLS TO FREELANCERS VIA PROFILE
        // ═══════════════════════════════════════════════════════
        foreach ($freelancers as $data) {
            $user = $createdFreelancers[$data['email']];
            // We store skills on job_skills for jobs; profiles don't have direct skill pivot in this schema.
            // Skills are attached to jobs. Profile skills are shown via the profile page's skill display.
        }

        // ═══════════════════════════════════════════════════════
        //  5. JOBS
        // ═══════════════════════════════════════════════════════
        $itCat       = Category::where('name', 'like', '%Information Technology%')->first();
        $designCat   = Category::where('name', 'like', '%Design%')->first();
        $writingCat  = Category::where('name', 'like', '%Writing%')->first();
        $transCat    = Category::where('name', 'like', '%Translation%')->first();
        $tourismCat  = Category::where('name', 'like', '%Tourism%')->first();
        $engCat      = Category::where('name', 'like', '%Engineering%')->first();

        $btcPoster  = $createdPosters['tenzin.norbu@btc.bt'];
        $dhiPoster  = $createdPosters['chimi.dema@dhi.bt'];
        $bbsPoster  = $createdPosters['dorji.wangchuk@bbs.bt'];
        $rcbPoster  = $createdPosters['sangay.lhamo@rcb.bt'];
        $medPoster  = $createdPosters['rinzin.peldon@medicorp.bt'];
        $airPoster  = $createdPosters['jigme.tsheltrim@drukair.bt'];

        $jobsData = [
            // ── IT Jobs ──
            [
                'poster_id'        => $dhiPoster->id,
                'category_id'      => $itCat?->id,
                'title'            => 'Develop E-Governance Portal for DHI Subsidiaries',
                'description'      => "Druk Holding & Investments requires a modern e-governance web portal that allows DHI's 27 subsidiaries to submit financial reports, track KPIs, and communicate with the holding company.\n\nThe portal must be built with Laravel, feature role-based access (subsidiary admin, DHI analyst, executive), have a responsive dashboard with charts, and export reports to PDF/Excel.\n\nAll data must be secured as per Bhutan's Information, Communications and Media Act.",
                'requirements'     => "- Minimum 3 years Laravel experience\n- Familiar with government-level security requirements\n- Experience with chart.js or similar\n- Able to deliver milestone-based with clear documentation",
                'type'             => 'fixed',
                'budget_min'       => 80000,
                'budget_max'       => 150000,
                'duration_days'    => 90,
                'experience_level' => 'expert',
                'dzongkhag'        => 'Thimphu',
                'remote_ok'        => true,
                'status'           => 'open',
                'is_featured'      => true,
                'views_count'      => 342,
                'proposals_count'  => 5,
                'skills'           => ['Web Development', 'Laravel', 'SQL & Database'],
            ],
            [
                'poster_id'        => $rcbPoster->id,
                'category_id'      => $itCat?->id,
                'title'            => 'Mobile App for RCB Motor Insurance Claims (iOS & Android)',
                'description'      => "Royal Insurance Corporation of Bhutan seeks a skilled Flutter developer to build a mobile application for motor insurance claims processing.\n\nFeatures required:\n- Customer portal: Register, view policy, submit claim with photos\n- Agent panel: Review and process claims\n- Push notifications for claim status updates\n- Offline form caching for remote areas\n- Integration with our existing REST API",
                'requirements'     => "- 2+ years Flutter/Dart\n- REST API integration experience\n- Published app on Play Store or App Store preferred",
                'type'             => 'fixed',
                'budget_min'       => 45000,
                'budget_max'       => 70000,
                'duration_days'    => 60,
                'experience_level' => 'intermediate',
                'dzongkhag'        => 'Thimphu',
                'remote_ok'        => true,
                'status'           => 'open',
                'is_featured'      => true,
                'views_count'      => 215,
                'proposals_count'  => 4,
                'skills'           => ['Mobile App Development', 'UI/UX Design'],
            ],
            [
                'poster_id'        => $dhiPoster->id,
                'category_id'      => $itCat?->id,
                'title'            => 'Data Analysis & Visualization Dashboard for Investment Portfolio',
                'description'      => "DHI needs a Python-based data analysis pipeline and a web dashboard to visualize the investment performance of its portfolio companies.\n\nScope includes data cleaning, trend analysis, Power BI/Tableau-style charts embedded in a Laravel admin panel. Monthly auto-report generation in PDF required.",
                'requirements'     => "- Proficient in Python (Pandas, Matplotlib or Plotly)\n- Experience with Laravel/Livewire\n- Understanding of financial KPIs",
                'type'             => 'fixed',
                'budget_min'       => 35000,
                'budget_max'       => 55000,
                'duration_days'    => 45,
                'experience_level' => 'expert',
                'dzongkhag'        => 'Thimphu',
                'remote_ok'        => true,
                'status'           => 'in_progress',
                'is_featured'      => false,
                'views_count'      => 178,
                'proposals_count'  => 3,
                'skills'           => ['Data Analysis', 'Python', 'Web Development'],
            ],
            // ── Design Jobs ──
            [
                'poster_id'        => $btcPoster->id,
                'category_id'      => $designCat?->id,
                'title'            => 'Rebrand Bhutan Tourism Council – Full Brand Identity Package',
                'description'      => "The Bhutan Tourism Council is refreshing its brand identity ahead of the 2027 Centennial Tourism Campaign.\n\nDeliverables:\n- New logo (primary + secondary marks)\n- Color palette, typography system\n- Brand guidelines document (PDF)\n- Business card, letterhead templates\n- Social media template pack (10 templates)\n\nThe design must incorporate elements of Bhutanese culture – dragon motifs, traditional patterns (kishuthara, etc.) – while remaining modern and internationally appealing.",
                'requirements'     => "- Strong portfolio of branding/identity work\n- Knowledge of Bhutanese aesthetics a big plus\n- Deliver source files (AI/PSD)",
                'type'             => 'fixed',
                'budget_min'       => 30000,
                'budget_max'       => 50000,
                'duration_days'    => 30,
                'experience_level' => 'expert',
                'dzongkhag'        => 'Thimphu',
                'remote_ok'        => true,
                'status'           => 'open',
                'is_featured'      => true,
                'views_count'      => 412,
                'proposals_count'  => 8,
                'skills'           => ['Logo Design', 'Branding', 'Graphic Design'],
            ],
            [
                'poster_id'        => $airPoster->id,
                'category_id'      => $designCat?->id,
                'title'            => 'Photography & Video Production for Druk Air 2026 Campaign',
                'description'      => "Druk Air is launching a 2026 marketing campaign to promote its new routes from Paro International Airport. We need a creative team (or a versatile freelancer) to capture high-quality photos and produce a 3-minute promotional video.\n\nLocations: Paro Valley, Tiger's Nest, and Thimphu city.\nDeliverables: 50+ edited photos, 3-min brand video, 6 x 30-sec social media cuts.\nDrone footage of Paro airport and monastery required.",
                'requirements'     => "- Professional camera gear (DSLR/Mirrorless + Drone with license)\n- Video editing proficiency (Premiere Pro or DaVinci Resolve)\n- Portfolio of commercial photography",
                'type'             => 'fixed',
                'budget_min'       => 40000,
                'budget_max'       => 65000,
                'duration_days'    => 14,
                'experience_level' => 'expert',
                'dzongkhag'        => 'Paro',
                'remote_ok'        => false,
                'status'           => 'open',
                'is_featured'      => true,
                'views_count'      => 290,
                'proposals_count'  => 6,
                'skills'           => ['Photography', 'Video Editing', 'Travel Photography'],
            ],
            [
                'poster_id'        => $medPoster->id,
                'category_id'      => $designCat?->id,
                'title'            => 'UI/UX Design for Telemedicine App',
                'description'      => "Medicorp Bhutan is developing a telemedicine platform to connect patients in remote dzongkhags to Thimphu-based doctors.\n\nWe need a UX/UI designer to create wireframes, user flows, and a polished Figma prototype. The app must be simple enough for elderly users in rural areas. Low-bandwidth adaptations required.",
                'requirements'     => "- Figma expertise\n- Experience designing healthcare or rural-facing apps\n- Understand accessibility guidelines",
                'type'             => 'fixed',
                'budget_min'       => 20000,
                'budget_max'       => 35000,
                'duration_days'    => 21,
                'experience_level' => 'intermediate',
                'dzongkhag'        => 'Thimphu',
                'remote_ok'        => true,
                'status'           => 'open',
                'is_featured'      => false,
                'views_count'      => 145,
                'proposals_count'  => 3,
                'skills'           => ['UI/UX Design', 'Graphic Design'],
            ],
            // ── Writing & Content Jobs ──
            [
                'poster_id'        => $btcPoster->id,
                'category_id'      => $writingCat?->id,
                'title'            => 'Write 20 SEO-Optimised Travel Blog Articles About Bhutan',
                'description'      => "Bhutan Tourism Council requires 20 long-form travel blog articles (min 1,200 words each) targeting international tourists planning trips to Bhutan.\n\nTopics include: best trekking routes, dzong architecture, Tshechu festivals, Bhutanese cuisine, sustainable tourism practices, and hidden gems by dzongkhag.\n\nAll articles must be original, SEO-optimised with provided keywords, and culturally accurate.",
                'requirements'     => "- Native or near-native English writing\n- Knowledge of Bhutan's tourism offerings\n- Provide 2 sample articles on similar topics",
                'type'             => 'fixed',
                'budget_min'       => 15000,
                'budget_max'       => 25000,
                'duration_days'    => 30,
                'experience_level' => 'intermediate',
                'dzongkhag'        => null,
                'remote_ok'        => true,
                'status'           => 'open',
                'is_featured'      => false,
                'views_count'      => 198,
                'proposals_count'  => 11,
                'skills'           => ['SEO Writing', 'Blog Writing', 'Research Writing'],
            ],
            [
                'poster_id'        => $bbsPoster->id,
                'category_id'      => $writingCat?->id,
                'title'            => 'Scriptwriting for BBS Documentary: "Bhutan\'s Carbon Neutral Journey"',
                'description'      => "Bhutan Broadcasting Service is producing a 45-minute documentary on Bhutan's journey to remain carbon neutral.\n\nWe need an experienced scriptwriter who can blend scientific facts with narrative storytelling. Interviews to be conducted with Ministry of Natural Resources officials, farmers from Haa, and environmental scientists.\n\nDeliverables: Research notes, interview questions, full shooting script.",
                'requirements'     => "- Experience in documentary or journalistic writing\n- Understanding of environmental topics\n- Ability to write in a compelling, accessible voice",
                'type'             => 'fixed',
                'budget_min'       => 18000,
                'budget_max'       => 28000,
                'duration_days'    => 25,
                'experience_level' => 'expert',
                'dzongkhag'        => 'Thimphu',
                'remote_ok'        => true,
                'status'           => 'in_progress',
                'is_featured'      => false,
                'views_count'      => 134,
                'proposals_count'  => 2,
                'skills'           => ['Script Writing', 'Research Writing', 'Blog Writing'],
            ],
            // ── Translation Jobs ──
            [
                'poster_id'        => $bbsPoster->id,
                'category_id'      => $transCat?->id,
                'title'            => 'Translate 200-Page Policy Document: English to Dzongkha',
                'description'      => "BBS requires a professional translator to translate a 200-page national communications policy document from English to Dzongkha. The document contains legal, technical, and administrative terminology specific to Bhutan's regulatory landscape.\n\nAccuracy, formal Dzongkha register, and adherence to standard government terminology are mandatory.",
                'requirements'     => "- Native Dzongkha speaker with formal writing proficiency\n- Previous experience translating official/government documents\n- Available for review rounds",
                'type'             => 'fixed',
                'budget_min'       => 20000,
                'budget_max'       => 30000,
                'duration_days'    => 20,
                'experience_level' => 'expert',
                'dzongkhag'        => null,
                'remote_ok'        => true,
                'status'           => 'open',
                'is_featured'      => false,
                'views_count'      => 167,
                'proposals_count'  => 4,
                'skills'           => ['Dzongkha Translation', 'Document Translation', 'English-Dzongkha'],
            ],
            [
                'poster_id'        => $medPoster->id,
                'category_id'      => $transCat?->id,
                'title'            => 'Dzongkha-English Medical Content Translation (Ongoing)',
                'description'      => "Medicorp is developing patient education materials in Dzongkha and needs an ongoing translator to convert English medical content to clear, simple Dzongkha.\n\nInitial scope: 50 patient information leaflets. If quality is good, long-term engagement to translate the full medical terminology database (2,000+ terms).",
                'requirements'     => "- Medical or health translation background preferred\n- Reliable, quick turnaround\n- Can work with Google Docs/shared folders",
                'type'             => 'hourly',
                'budget_min'       => 400,
                'budget_max'       => 700,
                'duration_days'    => 60,
                'experience_level' => 'intermediate',
                'dzongkhag'        => null,
                'remote_ok'        => true,
                'status'           => 'open',
                'is_featured'      => false,
                'views_count'      => 89,
                'proposals_count'  => 2,
                'skills'           => ['Dzongkha Translation', 'Document Translation', 'Transcription'],
            ],
            // ── Tourism Jobs ──
            [
                'poster_id'        => $btcPoster->id,
                'category_id'      => $tourismCat?->id,
                'title'            => 'Lead Trek Guide for 14-Day Snowman Trek (October Group)',
                'description'      => "Bhutan Tourism Council is managing a group of 8 international trekkers on the Snowman Trek in October 2026. We need an experienced, licensed lead guide with full knowledge of the route from Paro to Bumthang via Laya, Lunana and Tarina.\n\nThe guide must be certified by the Tourism Council of Bhutan, carry valid first aid certification, and be experienced with altitude sickness management.",
                'requirements'     => "- Government-licensed trekking guide\n- Snowman Trek experience mandatory\n- Wilderness First Aid certified\n- Fluent English",
                'type'             => 'fixed',
                'budget_min'       => 60000,
                'budget_max'       => 90000,
                'duration_days'    => 18,
                'experience_level' => 'expert',
                'dzongkhag'        => 'Paro',
                'remote_ok'        => false,
                'status'           => 'open',
                'is_featured'      => true,
                'views_count'      => 320,
                'proposals_count'  => 3,
                'skills'           => ['Trekking Guide', 'Cultural Tourism', 'Tour Guiding'],
            ],
            // ── Engineering Jobs ──
            [
                'poster_id'        => $dhiPoster->id,
                'category_id'      => $engCat?->id,
                'title'            => 'Structural Design & AutoCAD Drawings for Warehousing Facility',
                'description'      => "DHI subsidiary requires full structural design and AutoCAD drawings for a 5,000 sq.ft. warehousing facility to be built in Pasakha Industrial Estate.\n\nDeliverables: Architectural plans, structural drawings, electrical layout, elevation drawings, and Bill of Quantities. Must comply with Bhutan Building Rules and Regulations 2010.",
                'requirements'     => "- Licensed civil/structural engineer in Bhutan\n- AutoCAD proficiency\n- Experience with industrial buildings",
                'type'             => 'fixed',
                'budget_min'       => 50000,
                'budget_max'       => 80000,
                'duration_days'    => 30,
                'experience_level' => 'expert',
                'dzongkhag'        => 'Chukha',
                'remote_ok'        => false,
                'status'           => 'open',
                'is_featured'      => false,
                'views_count'      => 112,
                'proposals_count'  => 2,
                'skills'           => ['AutoCAD Drafting', 'Structural Design', 'Civil Engineering'],
            ],
        ];

        $createdJobs = [];
        foreach ($jobsData as $i => $jd) {
            $skills = $jd['skills'];
            unset($jd['skills']);
            $jd['slug'] = Str::slug($jd['title']) . '-' . ($jd['poster_id'] * 7 + $i);
            $jd['expires_at'] = now()->addDays(30);

            $job = Job::create($jd);

            // Attach skills
            foreach ($skills as $skillName) {
                $skill = Skill::where('name', $skillName)->first();
                if ($skill) {
                    $job->skills()->syncWithoutDetaching([$skill->id]);
                }
            }

            $createdJobs[] = $job;
        }

        // ═══════════════════════════════════════════════════════
        //  6. PROPOSALS
        // ═══════════════════════════════════════════════════════
        $kinley  = $createdFreelancers['kinley.dorji@demo.bt'];
        $tshering= $createdFreelancers['tshering.wangmo@demo.bt'];
        $sonam   = $createdFreelancers['sonam.choden@demo.bt'];
        $ugyen   = $createdFreelancers['ugyen.dorji@demo.bt'];
        $pema    = $createdFreelancers['pema.lhamo@demo.bt'];
        $karma   = $createdFreelancers['karma.tenzin@demo.bt'];
        $dechen  = $createdFreelancers['dechen.yangzom@demo.bt'];
        $namgay  = $createdFreelancers['namgay.tshering@demo.bt'];

        // Job[0] = DHI portal → Kinley (accepted), Dechen (pending)
        $proposal1 = Proposal::create([
            'job_id'        => $createdJobs[0]->id,
            'freelancer_id' => $kinley->id,
            'cover_letter'  => "Dear DHI team,\n\nI am excited to apply for the E-Governance Portal project. Having built the National Land Commission's property registry portal in 2023 and a reporting system for the Royal Civil Service Commission in 2024, I have deep experience with government-grade Laravel applications with RBAC, audit logging, and PDF/Excel exports.\n\nMy proposed approach: 3 milestones over 90 days – Phase 1 (user auth & role management), Phase 2 (dashboard & KPI reporting), Phase 3 (testing, security audit & deployment on Bhutan Telecom cloud servers).\n\nI am available for a meeting at DHI offices in Thimphu at your convenience.",
            'bid_amount'    => 125000,
            'delivery_days' => 85,
            'status'        => 'accepted',
            'is_shortlisted'=> true,
            'shortlisted_at'=> now()->subDays(20),
            'awarded_at'    => now()->subDays(15),
        ]);

        $proposal2 = Proposal::create([
            'job_id'        => $createdJobs[0]->id,
            'freelancer_id' => $dechen->id,
            'cover_letter'  => "Good day,\n\nI would like to bid for the DHI e-governance portal. My Flutter and web development background, combined with experience in React dashboards for financial clients, makes me a strong fit. I can deliver within 90 days for Nu. 120,000.",
            'bid_amount'    => 120000,
            'delivery_days' => 90,
            'status'        => 'pending',
            'is_shortlisted'=> false,
        ]);

        // Job[1] = RCB mobile app → Dechen (accepted)
        $proposal3 = Proposal::create([
            'job_id'        => $createdJobs[1]->id,
            'freelancer_id' => $dechen->id,
            'cover_letter'  => "Hello,\n\nI am a Flutter developer with 4 years of experience building cross-platform insurance and fintech apps. I developed the Karma Insurance mobile app last year with similar claim submission and photo upload features. I can integrate with your existing REST API and deliver a polished app with offline support in 55 days.\n\nI am Bhutanese and based in Thimphu, so in-person meetings are easy.",
            'bid_amount'    => 62000,
            'delivery_days' => 55,
            'status'        => 'accepted',
            'is_shortlisted'=> true,
            'shortlisted_at'=> now()->subDays(10),
            'awarded_at'    => now()->subDays(7),
        ]);

        // Job[3] = BTC branding → Tshering (accepted)
        $proposal4 = Proposal::create([
            'job_id'        => $createdJobs[3]->id,
            'freelancer_id' => $tshering->id,
            'cover_letter'  => "Dear Bhutan Tourism Council,\n\nAs a designer deeply rooted in Bhutanese visual culture, I am thrilled to propose for your brand identity refresh. My portfolio includes the Paro Eco-Lodge rebrand, Bhutan Spirit Sanctuary collateral, and digital brand guidelines for two Tour Operators.\n\nI will draw inspiration from the kishuthara textile pattern and the druk motif while delivering a clean, internationally competitive identity. All files delivered in Illustrator and ready for print and digital use.",
            'bid_amount'    => 45000,
            'delivery_days' => 28,
            'status'        => 'accepted',
            'is_shortlisted'=> true,
            'shortlisted_at'=> now()->subDays(8),
            'awarded_at'    => now()->subDays(5),
        ]);

        // Job[4] = Druk Air photography → Namgay (pending, shortlisted)
        $proposal5 = Proposal::create([
            'job_id'        => $createdJobs[4]->id,
            'freelancer_id' => $namgay->id,
            'cover_letter'  => "Dear Druk Air team,\n\nI am Namgay Tshering, an award-winning photographer based in Wangdue who has shot for Bhutan's tourism and aviation sector for six years. I have a DJI Mavic 3 Pro drone with DNCA clearance for aerial work around Paro airport and Tiger's Nest.\n\nI propose a 12-day shoot covering all locations, delivering 80+ edited photos and the full video package. My work has been published in Kuensel and National Geographic Traveller India.",
            'bid_amount'    => 58000,
            'delivery_days' => 12,
            'status'        => 'shortlisted',
            'is_shortlisted'=> true,
            'shortlisted_at'=> now()->subDays(2),
        ]);

        // Job[6] = BTC travel blogs → Pema (pending)
        $proposal6 = Proposal::create([
            'job_id'        => $createdJobs[6]->id,
            'freelancer_id' => $pema->id,
            'cover_letter'  => "Hello BTC team,\n\nI am a travel content writer who has contributed to Lonely Planet's Bhutan edition and Business Bhutan's digital magazine. I can write all 20 articles with cultural accuracy, proper keyword integration, and engaging storytelling that converts international readers into visitors.\n\nI will deliver 4 articles per week. Each article includes schema markup suggestions and image alt text recommendations for your SEO team.",
            'bid_amount'    => 22000,
            'delivery_days' => 28,
            'status'        => 'pending',
            'is_shortlisted'=> false,
        ]);

        // Job[8] = BBS Dzongkha translation → Sonam (accepted)
        $proposal7 = Proposal::create([
            'job_id'        => $createdJobs[8]->id,
            'freelancer_id' => $sonam->id,
            'cover_letter'  => "Dear BBS,\n\nI have translated over 40 policy and legal documents for the Royal Government and several UN agencies in Bhutan. The communications policy falls directly within my expertise. I guarantee formal Dzongkha register, consistent use of official terminology, and delivery within 18 days.\n\nI am available for review sessions by Zoom or in-person in Thimphu.",
            'bid_amount'    => 26000,
            'delivery_days' => 18,
            'status'        => 'accepted',
            'is_shortlisted'=> true,
            'shortlisted_at'=> now()->subDays(12),
            'awarded_at'    => now()->subDays(9),
        ]);

        // Job[10] = BTC Snowman Trek → Karma (accepted)
        $proposal8 = Proposal::create([
            'job_id'        => $createdJobs[10]->id,
            'freelancer_id' => $karma->id,
            'cover_letter'  => "Dear Bhutan Tourism Council,\n\nI have led the Snowman Trek 9 times since 2014, including 3 times for BTC-managed groups. I hold a valid TCB guide license, Wilderness First Aid certification, and evacuation procedures training.\n\nI am fully available for October 2026 and will prepare a detailed pre-trek briefing, daily itinerary, and contingency plans. My rate is Nu. 75,000 inclusive of logistics coordination.",
            'bid_amount'    => 75000,
            'delivery_days' => 18,
            'status'        => 'accepted',
            'is_shortlisted'=> true,
            'shortlisted_at'=> now()->subDays(5),
            'awarded_at'    => now()->subDays(3),
        ]);

        // Job[11] = DHI engineering → Ugyen (pending)
        $proposal9 = Proposal::create([
            'job_id'        => $createdJobs[11]->id,
            'freelancer_id' => $ugyen->id,
            'cover_letter'  => "Dear DHI,\n\nI am a licensed civil and structural engineer with experience designing industrial buildings in Pasakha and Gelephu industrial estates. I have full AutoCAD and SAP2000 proficiency and am familiar with the Bhutan Building Rules 2010 and fire safety codes.\n\nI can complete the full drawings package including BoQ in 28 days. I can visit the site in Pasakha as needed.",
            'bid_amount'    => 70000,
            'delivery_days' => 28,
            'status'        => 'pending',
            'is_shortlisted'=> false,
        ]);

        // ═══════════════════════════════════════════════════════
        //  7. CONTRACTS (for accepted proposals)
        // ═══════════════════════════════════════════════════════
        $contractsData = [
            // DHI portal – Kinley
            [
                'contract_number'   => 'DF-2026-00001',
                'job_id'            => $createdJobs[0]->id,
                'proposal_id'       => $proposal1->id,
                'poster_id'         => $dhiPoster->id,
                'freelancer_id'     => $kinley->id,
                'terms'             => "This contract is entered between Druk Holding & Investments (Client) and Kinley Dorji (Freelancer) for the development of the DHI E-Governance Portal.\n\n1. The Freelancer agrees to deliver the portal in three milestones as detailed below.\n2. Payment will be released upon milestone approval by the Client.\n3. The Freelancer agrees to rectify any bugs within 30 days of final delivery at no additional cost.\n4. Source code ownership transfers to DHI upon full payment.",
                'total_amount'      => 125000,
                'platform_fee'      => 12500,
                'freelancer_amount' => 112500,
                'status'            => 'active',
                'start_date'        => now()->subDays(14),
                'deadline'          => now()->addDays(71),
                'poster_signed'     => true,
                'freelancer_signed' => true,
                'milestones'        => [
                    ['title' => 'Phase 1: Auth, Roles & DB Architecture', 'description' => 'User authentication, role-based access control, database schema design and migration. Delivery includes ER diagram and system architecture document.', 'amount' => 35000, 'due_date' => now()->subDays(14)->addDays(25), 'sort_order' => 1, 'status' => 'approved'],
                    ['title' => 'Phase 2: Dashboard, KPI Reporting & Notifications', 'description' => 'Interactive dashboards for each role, financial KPI charts, in-app notifications, and subsidiary report submission forms.', 'amount' => 55000, 'due_date' => now()->subDays(14)->addDays(60), 'sort_order' => 2, 'status' => 'submitted'],
                    ['title' => 'Phase 3: Security Audit, Testing & Deployment', 'description' => 'Full security audit, penetration testing, bug fixes, UAT with DHI team, and deployment on Bhutan Telecom cloud servers.', 'amount' => 35000, 'due_date' => now()->subDays(14)->addDays(85), 'sort_order' => 3, 'status' => 'pending'],
                ],
            ],
            // RCB mobile app – Dechen
            [
                'contract_number'   => 'DF-2026-00002',
                'job_id'            => $createdJobs[1]->id,
                'proposal_id'       => $proposal3->id,
                'poster_id'         => $rcbPoster->id,
                'freelancer_id'     => $dechen->id,
                'terms'             => "This agreement covers the development of the RCB Motor Insurance Claims Mobile Application by Dechen Yangzom for the Royal Insurance Corporation of Bhutan.\n\n1. Two milestones: UI/UX prototype and functional app delivery.\n2. The app must pass RCB's internal QA before final payment.\n3. One month post-launch support included.",
                'total_amount'      => 62000,
                'platform_fee'      => 6200,
                'freelancer_amount' => 55800,
                'status'            => 'active',
                'start_date'        => now()->subDays(6),
                'deadline'          => now()->addDays(49),
                'poster_signed'     => true,
                'freelancer_signed' => true,
                'milestones'        => [
                    ['title' => 'UI/UX Prototype & API Integration Plan', 'description' => 'Complete Figma prototype for all user flows (customer + agent), API endpoint documentation review, and offline caching strategy.', 'amount' => 20000, 'due_date' => now()->subDays(6)->addDays(18), 'sort_order' => 1, 'status' => 'pending'],
                    ['title' => 'Working App – Both Platforms', 'description' => 'Functional Flutter app on iOS and Android with all agreed features: policy view, claim submission, photo upload, push notifications, agent panel.', 'amount' => 42000, 'due_date' => now()->subDays(6)->addDays(55), 'sort_order' => 2, 'status' => 'pending'],
                ],
            ],
            // BTC branding – Tshering
            [
                'contract_number'   => 'DF-2026-00003',
                'job_id'            => $createdJobs[3]->id,
                'proposal_id'       => $proposal4->id,
                'poster_id'         => $btcPoster->id,
                'freelancer_id'     => $tshering->id,
                'terms'             => "Tshering Wangmo (Designer) is engaged by Bhutan Tourism Council (Client) to deliver a full brand identity package.\n\n1. All work is original and created exclusively for BTC.\n2. The Client has unlimited revisions on logo at concept stage; max 2 revisions after concept approval.\n3. Full copyright and IP transfers to BTC upon final payment.",
                'total_amount'      => 45000,
                'platform_fee'      => 4500,
                'freelancer_amount' => 40500,
                'status'            => 'completed',
                'start_date'        => now()->subDays(30),
                'deadline'          => now()->subDays(2),
                'completed_at'      => now()->subDays(1),
                'poster_signed'     => true,
                'freelancer_signed' => true,
                'milestones'        => [
                    ['title' => 'Logo Concepts (3 Directions)', 'description' => '3 distinct logo concepts exploring different directions: dragon motif, landscape inspired, and pattern-driven designs.', 'amount' => 15000, 'due_date' => now()->subDays(20), 'sort_order' => 1, 'status' => 'approved'],
                    ['title' => 'Final Logo & Brand Guidelines', 'description' => 'Refined logo in all formats, full brand guidelines PDF, color system, typography, do/don\'t guide.', 'amount' => 18000, 'due_date' => now()->subDays(10), 'sort_order' => 2, 'status' => 'approved'],
                    ['title' => 'Templates: Stationery & Social Media Pack', 'description' => 'Business card, letterhead, email signature, and 10 social media templates for Instagram/Facebook.', 'amount' => 12000, 'due_date' => now()->subDays(2), 'sort_order' => 3, 'status' => 'approved'],
                ],
            ],
            // BBS translation – Sonam
            [
                'contract_number'   => 'DF-2026-00004',
                'job_id'            => $createdJobs[8]->id,
                'proposal_id'       => $proposal7->id,
                'poster_id'         => $bbsPoster->id,
                'freelancer_id'     => $sonam->id,
                'terms'             => "This contract engages Sonam Choden to translate the BBS National Communications Policy Document from English to Dzongkha.\n\n1. Translation must use official government Dzongkha terminology.\n2. Two review rounds are included in the scope.\n3. BBS retains all rights to the translated document.",
                'total_amount'      => 26000,
                'platform_fee'      => 2600,
                'freelancer_amount' => 23400,
                'status'            => 'active',
                'start_date'        => now()->subDays(8),
                'deadline'          => now()->addDays(10),
                'poster_signed'     => true,
                'freelancer_signed' => true,
                'milestones'        => [
                    ['title' => 'Part 1: Pages 1-100 Translation', 'description' => 'First 100 pages of the policy document translated to Dzongkha with terminology notes.', 'amount' => 13000, 'due_date' => now()->subDays(8)->addDays(10), 'sort_order' => 1, 'status' => 'approved'],
                    ['title' => 'Part 2: Pages 101-200 + Final Review', 'description' => 'Remaining 100 pages translated plus a complete proofreading pass and final Dzongkha term consistency check.', 'amount' => 13000, 'due_date' => now()->subDays(8)->addDays(18), 'sort_order' => 2, 'status' => 'pending'],
                ],
            ],
        ];

        $contractNum = 1;
        foreach ($contractsData as $cd) {
            $milestones = $cd['milestones'];
            unset($cd['milestones']);
            $contract = Contract::create($cd);
            $contractNum++;

            foreach ($milestones as $ms) {
                $msStatus = $ms['status'];
                unset($ms['status']);
                $ms['contract_id'] = $contract->id;

                // Set dates based on status
                $msData = array_merge($ms, [
                    'status'       => $msStatus,
                    'submitted_at' => in_array($msStatus, ['submitted', 'approved']) ? now()->subDays(3) : null,
                    'approved_at'  => $msStatus === 'approved' ? now()->subDays(1) : null,
                ]);
                Milestone::create($msData);
            }
        }

        // ═══════════════════════════════════════════════════════
        //  8. REVIEWS (for completed contract)
        // ═══════════════════════════════════════════════════════
        $completedContract = Contract::where('contract_number', 'DF-2026-00003')->first();
        if ($completedContract) {
            // Poster reviews freelancer
            Review::create([
                'contract_id'          => $completedContract->id,
                'reviewer_id'          => $btcPoster->id,
                'reviewee_id'          => $tshering->id,
                'reviewer_role'        => 'poster',
                'rating_overall'       => 5,
                'rating_communication' => 5,
                'rating_quality'       => 5,
                'rating_timeliness'    => 5,
                'rating_professionalism' => 5,
                'comment'              => 'Tshering delivered outstanding brand identity work that perfectly captured the essence of Bhutanese culture while remaining modern and internationally competitive. The druk motif integration was subtle yet powerful. Delivered on time, highly professional and responsive throughout. Highly recommended for any branding project in Bhutan.',
                'is_public'            => true,
            ]);

            // Freelancer reviews poster
            Review::create([
                'contract_id'          => $completedContract->id,
                'reviewer_id'          => $tshering->id,
                'reviewee_id'          => $btcPoster->id,
                'reviewer_role'        => 'freelancer',
                'rating_overall'       => 5,
                'rating_communication' => 5,
                'rating_quality'       => 5,
                'rating_timeliness'    => 5,
                'rating_professionalism' => 5,
                'comment'              => 'Working with Bhutan Tourism Council was a pleasure. Tenzin la provided a very clear brief, gave constructive feedback, and approved milestones promptly. The project was everything a designer hopes for. Would love to collaborate again on the 2027 centennial campaign.',
                'is_public'            => true,
            ]);
        }

        // ═══════════════════════════════════════════════════════
        //  9. TRANSACTIONS
        // ═══════════════════════════════════════════════════════
        $txs = [
            ['user_id' => $dhiPoster->id,  'type' => 'deposit',         'amount' => 200000, 'net_amount' => 200000, 'notes' => 'Wallet top-up via BNB bank transfer'],
            ['user_id' => $dhiPoster->id,  'type' => 'escrow_hold',     'amount' => 35000,  'net_amount' => 35000,  'notes' => 'Escrow: Phase 1 DHI Portal (DF-2026-00001)'],
            ['user_id' => $kinley->id,     'type' => 'escrow_release',  'amount' => 31500,  'net_amount' => 31500,  'notes' => 'Milestone paid: Phase 1 DHI Portal (DF-2026-00001)'],
            ['user_id' => $dhiPoster->id,  'type' => 'platform_fee',    'amount' => 3500,   'net_amount' => 3500,   'notes' => 'Platform fee: Phase 1 DHI Portal'],
            ['user_id' => $btcPoster->id,  'type' => 'deposit',         'amount' => 100000, 'net_amount' => 100000, 'notes' => 'Wallet top-up via BNBL bank transfer'],
            ['user_id' => $btcPoster->id,  'type' => 'escrow_hold',     'amount' => 45000,  'net_amount' => 45000,  'notes' => 'Escrow: BTC Brand Identity (DF-2026-00003)'],
            ['user_id' => $tshering->id,   'type' => 'escrow_release',  'amount' => 40500,  'net_amount' => 40500,  'notes' => 'Full payment: BTC Brand Identity (DF-2026-00003)'],
            ['user_id' => $btcPoster->id,  'type' => 'platform_fee',    'amount' => 4500,   'net_amount' => 4500,   'notes' => 'Platform fee: BTC Brand Identity'],
            ['user_id' => $bbsPoster->id,  'type' => 'deposit',         'amount' => 50000,  'net_amount' => 50000,  'notes' => 'Wallet top-up via Bhutan National Bank'],
            ['user_id' => $bbsPoster->id,  'type' => 'escrow_hold',     'amount' => 13000,  'net_amount' => 13000,  'notes' => 'Escrow: Part 1 Translation (DF-2026-00004)'],
            ['user_id' => $sonam->id,      'type' => 'escrow_release',  'amount' => 11700,  'net_amount' => 11700,  'notes' => 'Milestone paid: Pages 1-100 Translation (DF-2026-00004)'],
            ['user_id' => $rcbPoster->id,  'type' => 'deposit',         'amount' => 80000,  'net_amount' => 80000,  'notes' => 'Wallet top-up via RCB Treasury'],
        ];

        foreach ($txs as $i => $tx) {
            Transaction::create(array_merge($tx, [
                'transaction_ref'  => 'TXN-2026-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'fee'              => 0,
                'status'           => 'completed',
                'payment_provider' => 'internal',
                'created_at'       => now()->subDays(rand(1, 20)),
            ]));
        }

        $this->command->info('✅ Bhutan demo data seeded successfully!');
        $this->command->info('   Freelancers & Posters password: Demo@1234');
        $this->command->line('');
        $this->command->table(
            ['Role', 'Name', 'Email'],
            collect($freelancers)->map(fn($f) => ['Freelancer', $f['name'], $f['email']])->concat(
                collect($posters)->map(fn($p) => ['Job Poster', $p['name'], $p['email']])
            )->toArray()
        );
    }
}
