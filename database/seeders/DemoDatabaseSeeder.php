<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\ActivityType;
use App\Models\Activity;
use App\Models\Comment;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use Arr;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * This seeder is used to generate demo data for the application.
 */
class DemoDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $projectsNumber = 200 / 10;
        $tasksPerProject = 800 / 10;
        $maxCommentsNumber = 40 / 10;

        DB::table('comments')->truncate();
        DB::table('activities')->truncate();
        DB::table('times')->truncate();
        DB::table('task_users')->truncate();
        DB::table('tasks')->truncate();
        DB::table('project_members')->truncate();
        DB::table('projects')->truncate();
        DB::table('group_users')->truncate();
        DB::table('groups')->truncate();
        DB::table('users')->truncate();

        $this->call(GroupsSeeder::class);
        $this->call(PrioritiesSeeder::class);
        $this->call(StatusesSeeder::class);

        $faker = Factory::create();

        /** @var User $user */
        $user = User::factory()->create([
            'first_name' => 'Adam',
            'last_name' => 'Smith',
            'email' => 'admin@demo.com',
            'password' => bcrypt('demo'),
        ]);

        $user->groups()->attach(1);

        // create random 20 team members

        User::factory(20)->create()->each(function ($user) {
            $user->groups()->attach(3);
        });

        $users = User::all();

        shuffle($this->projects);
        for ($i = 0; $i < min($projectsNumber, count($this->projects)); $i++) {
            $project = Project::create($this->projects[$i]);
            shuffle($this->tasks);

            // Create and assign client user
            $client = User::factory()->create();
            assert($client instanceof User);

            $client->groups()->attach(2);
            $project->client_id = $client->id;
            $project->save();

            // assign 5 random team members to each project
            $projectMembers = $users->random(5)->pluck('id');
            $project->members()->attach($projectMembers);

            shuffle($this->tasks);
            for ($x = 0; $x < min($tasksPerProject, count($this->tasks)); $x++) {
                $task = new Task($this->tasks[$x]);
                $task['author_id'] = $projectMembers->random();
                $task['created_at'] = $faker->dateTimeBetween('-90 days');
                $task['subject'] .= ' ' . Arr::random(['🚀', '', '', '', '', '', '', '', '', '', '']);
                $task['status_id'] = Status::query()->inRandomOrder()->first()->id;
                $task['priority_id'] = Priority::query()->inRandomOrder()->first()->id;

                $task['description'] .= '<p></p><pre><code>// This is a code example
while($task->isNotCompleted()){
   $user->workOnTask($task);
}</code></pre>';

                $project->tasks()->save($task);
            }

            foreach (Task::whereProjectId($project->id)->get() as $task) {
                $days = rand(1, 90);
                $start = rand(1, 500);
                $start_at = now()->subDays($days)->subMinutes($start);
                $end_at = now()->subDays($days)->addMinutes(rand(1, $start));
                $task->times()->create([
                    'author_id' => $projectMembers->random(),
                    'project_id' => $task->project_id,
                    'start_at' => $start_at,
                    'end_at' => $end_at,
                    'created_at' => $end_at,
                ]);

                $comments = $this->comments;
                shuffle($comments);
                for ($y = 0; $y < rand(0, $maxCommentsNumber); $y++) {
                    $comment = Comment::query()->create([
                        'content' => array_shift($comments)['comment'],
                    ]);

                    assert($comment instanceof Comment);

                    $activity = $task->activities()->create([
                        'created_at' => $faker->dateTimeBetween($task->created_at, now()),
                        'user_id' => $projectMembers->random(),
                        'project_id' => $task->project_id,
                        'type' => ActivityType::TASK_COMMENTED,
                        'comment_id' => $comment->id,
                    ]);

                    assert($activity instanceof Activity);

                    $activity->comment()->associate($comment);
                }
            }
        }
    }

    public array $projects = [
        ['name' => 'Crimson', 'description' => 'A revolutionary social media platform for creative minds.'],
        ['name' => 'Luminex', 'description' => 'An advanced AI-powered virtual assistant for professionals.'],
        ['name' => 'Nebula', 'description' => 'A cloud-based project collaboration tool for remote teams.'],
        ['name' => 'Aurora', 'description' => 'An innovative e-commerce platform for personalized shopping experiences.'],
        ['name' => 'Helios', 'description' => 'A solar energy management system for efficient power generation.'],
        ['name' => 'Quicksilver', 'description' => 'A lightning-fast website optimization tool for developers.'],
        ['name' => 'Zenith', 'description' => 'A comprehensive financial planning platform for individuals and businesses.'],
        ['name' => 'Nova', 'description' => 'A next-generation video streaming service with exclusive content.'],
        ['name' => 'Polaris', 'description' => 'A blockchain-based solution for transparent supply chain management.'],
        ['name' => 'Stellar', 'description' => 'An intuitive task management app for productivity enthusiasts.'],
        ['name' => 'Vortex', 'description' => 'A virtual reality gaming console with immersive gameplay experiences.'],
        ['name' => 'Galaxy', 'description' => 'A cross-platform mobile app for effortless communication and file sharing.'],
        ['name' => 'Lumos', 'description' => 'An AI-powered smart home automation system for modern households.'],
        ['name' => 'Spectrum', 'description' => 'A data visualization tool for analyzing complex datasets.'],
        ['name' => 'Vertex', 'description' => 'A 3D modeling software for architects and designers.'],
        ['name' => 'Horizon', 'description' => 'A fitness tracking app with personalized workout plans and progress tracking.'],
        ['name' => 'Eclipse', 'description' => 'A comprehensive event management platform for organizing conferences and workshops.'],
        ['name' => 'Nimbus', 'description' => 'A weather forecasting app with real-time updates and interactive radar.'],
        ['name' => 'Aether', 'description' => 'An AI-driven medical diagnosis system for early disease detection.'],
        ['name' => 'Typhoon', 'description' => 'A robust project management software for efficient team collaboration.'],
    ];

    public array $tasks = [
        ['subject' => 'Create website wireframes', 'description' => 'Design wireframes for the new website'],
        ['subject' => 'Develop user registration feature', 'description' => 'Implement user registration functionality with email verification'],
        ['subject' => 'Write blog post about industry trends', 'description' => 'Compose an article discussing the latest trends in the industry'],
        ['subject' => 'Test website on different devices', 'description' => 'Perform cross-device testing to ensure website compatibility'],
        ['subject' => 'Optimize website loading speed', 'description' => 'Improve website performance by optimizing loading speed'],
        ['subject' => 'Prepare presentation for client meeting', 'description' => 'Create a visually appealing presentation to showcase project progress'],
        ['subject' => 'Research competitor analysis', 'description' => 'Gather information on competitor strategies and analyze their strengths and weaknesses'],
        ['subject' => 'Create social media marketing campaign', 'description' => 'Develop a marketing campaign for promoting the brand on social media platforms'],
        ['subject' => 'Implement payment gateway integration', 'description' => 'Integrate a secure payment gateway to facilitate online transactions'],
        ['subject' => 'Write user documentation', 'description' => 'Prepare comprehensive documentation to assist users in navigating the system'],
        ['subject' => 'Conduct market research survey', 'description' => 'Design and distribute a survey to gather feedback and insights from target market'],
        ['subject' => 'Prepare budget proposal', 'description' => 'Create a detailed budget plan for the upcoming project phase'],
        ['subject' => 'Update website content', 'description' => 'Review and refresh website content to reflect current information and trends'],
        ['subject' => 'Design product packaging', 'description' => 'Create visually appealing packaging design for a new product'],
        ['subject' => 'Perform database backup', 'description' => 'Execute a backup of the project database for data security'],
        ['subject' => 'Translate website content to French', 'description' => 'Translate website text and content to French language'],
        ['subject' => 'Create video tutorial for new feature', 'description' => 'Record and edit a tutorial video demonstrating the usage of a new feature'],
        ['subject' => 'Implement SEO strategies', 'description' => 'Optimize website content and structure to improve search engine rankings'],
        ['subject' => 'Coordinate team meeting', 'description' => 'Schedule and organize a team meeting to discuss project updates'],
        ['subject' => 'Design company logo', 'description' => 'Create a unique and visually appealing logo for the company'],
        ['subject' => 'Review and approve design mockups', 'description' => 'Evaluate and provide feedback on design mockups for the new website'],
        ['subject' => 'Develop mobile app interface', 'description' => 'Design and implement user interface for the mobile application'],
        ['subject' => 'Create marketing email campaign', 'description' => 'Design and send a series of promotional emails to target audience'],
        ['subject' => 'Perform security vulnerability testing', 'description' => 'Identify and address security vulnerabilities in the system'],
        ['subject' => 'Optimize website for mobile devices', 'description' => 'Ensure that the website is fully responsive and optimized for mobile users'],
        ['subject' => 'Write technical documentation', 'description' => 'Prepare detailed technical documentation for system maintenance'],
        ['subject' => 'Conduct user acceptance testing', 'description' => 'Invite users to test the system and provide feedback on its usability'],
        ['subject' => 'Create content calendar', 'description' => 'Plan and schedule content for social media platforms and blog'],
        ['subject' => 'Perform competitor keyword analysis', 'description' => 'Research and analyze keywords used by competitors for SEO optimization'],
        ['subject' => 'Implement multi-language support', 'description' => 'Enable the system to support multiple languages for international users'],
        ['subject' => 'Create customer satisfaction survey', 'description' => 'Design a survey to gather feedback and measure customer satisfaction'],
        ['subject' => 'Develop product roadmap', 'description' => 'Outline the future development and enhancements for the product'],
        ['subject' => 'Integrate Google Analytics', 'description' => 'Implement Google Analytics to track website traffic and user behavior'],
        ['subject' => 'Write user guide for new feature', 'description' => 'Create a user-friendly guide explaining the usage of a recently added feature'],
        ['subject' => 'Create promotional video', 'description' => 'Produce a captivating video to promote the company and its offerings'],
        ['subject' => 'Perform system performance testing', 'description' => 'Conduct tests to evaluate the system\'s performance under different scenarios'],
        ['subject' => 'Optimize website for search engines', 'description' => 'Improve website visibility by implementing SEO best practices'],
        ['subject' => 'Develop social media content strategy', 'description' => 'Plan and define the content strategy for social media platforms'],
        ['subject' => 'Conduct usability testing', 'description' => 'Evaluate the system\'s usability through user testing sessions'],
        ['subject' => 'Prepare quarterly financial report', 'description' => 'Compile financial data and generate a report for the last quarter'],
        ['subject' => 'Create user interface wireframes', 'description' => 'Design wireframes for the user interface of the new system'],
        ['subject' => 'Develop email newsletter template', 'description' => 'Design and code an email template for the company\'s newsletter'],
        ['subject' => 'Perform system backup and recovery testing', 'description' => 'Test the system\'s backup and recovery process to ensure data integrity'],
        ['subject' => 'Research target audience demographics', 'description' => 'Gather data on the characteristics and preferences of the target audience'],
        ['subject' => 'Implement user roles and permissions', 'description' => 'Define and assign different user roles with appropriate system permissions'],
        ['subject' => 'Create online advertising campaign', 'description' => 'Plan and execute an online advertising campaign to increase brand awareness'],
        ['subject' => 'Perform A/B testing on landing page', 'description' => 'Conduct A/B tests to compare and optimize the performance of different landing page variations'],
        ['subject' => 'Write technical specifications document', 'description' => 'Prepare a detailed document outlining the technical specifications for the system'],
        ['subject' => 'Conduct customer feedback survey', 'description' => 'Collect feedback from customers to gauge their satisfaction and identify areas for improvement'],
        ['subject' => 'Design responsive email templates', 'description' => 'Create visually appealing and responsive email templates for various purposes'],
        ['subject' => 'Perform system integration testing', 'description' => 'Verify the compatibility and functionality of the system when integrated with other tools'],
        ['subject' => 'Prepare marketing budget plan', 'description' => 'Outline the budget allocation for marketing activities and campaigns'],
        ['subject' => 'Create support ticketing system', 'description' => 'Develop a system for managing and tracking customer support tickets'],
        ['subject' => 'Perform website accessibility audit', 'description' => 'Ensure that the website is accessible to users with disabilities'],
        ['subject' => 'Develop customer loyalty program', 'description' => 'Design and implement a program to reward and retain loyal customers'],
        ['subject' => 'Conduct focus group session', 'description' => 'Organize a focus group to gather feedback and insights from a specific target audience'],
        ['subject' => 'Design product user interface', 'description' => 'Create an intuitive and user-friendly interface for the product'],
        ['subject' => 'Implement social media sharing functionality', 'description' => 'Enable users to share content from the system on social media platforms'],
        ['subject' => 'Write technical blog post', 'description' => 'Compose an informative blog post explaining a technical concept or feature'],
        ['subject' => 'Create customer onboarding process', 'description' => 'Develop a systematic process to onboard new customers and ensure a smooth transition'],
        ['subject' => 'Perform load testing on server infrastructure', 'description' => 'Simulate high traffic loads to assess the performance of the server infrastructure'],
        ['subject' => 'Design banner ads for online advertising', 'description' => 'Create visually appealing banner ads for display on websites and social media platforms'],
        ['subject' => 'Perform user interface usability testing', 'description' => 'Evaluate the usability of the user interface through user testing sessions'],
        ['subject' => 'Optimize website for conversion rate', 'description' => 'Analyze and enhance the website to maximize the conversion rate of visitors to customers'],
        ['subject' => 'Develop customer feedback management system', 'description' => 'Build a system to collect, organize, and analyze customer feedback'],
        ['subject' => 'Conduct employee training session', 'description' => 'Deliver a training session to educate employees on new processes or tools'],
        ['subject' => 'Design product packaging label', 'description' => 'Create an attractive and informative label design for the product packaging'],
        ['subject' => 'Implement two-factor authentication', 'description' => 'Enhance security by adding a two-factor authentication system'],
        ['subject' => 'Create content for email marketing campaign', 'description' => 'Write engaging content for email marketing campaigns to increase conversions'],
        ['subject' => 'Perform system compatibility testing', 'description' => 'Ensure that the system functions correctly across different browsers and devices'],
        ['subject' => 'Develop customer relationship management (CRM) system', 'description' => 'Build a system to manage and track customer interactions and relationships'],
        ['subject' => 'Conduct website user experience (UX) audit', 'description' => 'Evaluate the website\'s user experience and suggest improvements'],
        ['subject' => 'Design product prototype', 'description' => 'Create a functional prototype of the product to test its features and usability'],
        ['subject' => 'Implement live chat support', 'description' => 'Integrate a live chat functionality to provide real-time support to website visitors'],
        ['subject' => 'Write user stories for agile development', 'description' => 'Create user stories to define requirements and guide the agile development process'],
        ['subject' => 'Create marketing personas', 'description' => 'Develop fictional personas representing target customer segments for marketing purposes'],
        ['subject' => 'Perform system data migration', 'description' => 'Migrate data from the old system to the new system without data loss or corruption'],
        ['subject' => 'Design landing page for lead generation', 'description' => 'Create a landing page optimized for lead generation and conversion'],
        ['subject' => 'Optimize website navigation and structure', 'description' => 'Improve the website\'s navigation menu and overall structure for better user experience'],
        ['subject' => 'Develop customer satisfaction measurement metrics', 'description' => 'Define metrics and surveys to measure and track customer satisfaction'],
        ['subject' => 'Conduct user feedback sessions', 'description' => 'Gather feedback from users to understand their needs and preferences'],
        ['subject' => 'Design product promotional materials', 'description' => 'Create brochures, flyers, and other promotional materials for the product'],
        ['subject' => 'Perform system code review', 'description' => 'Review and analyze the system\'s code for quality assurance and improvement'],
        ['subject' => 'Prepare sales forecast report', 'description' => 'Analyze market trends and prepare a sales forecast report for the upcoming quarter'],
        ['subject' => 'Create interactive website elements', 'description' => 'Design and implement interactive elements on the website to enhance user engagement'],
        ['subject' => 'Develop customer support knowledge base', 'description' => 'Build a comprehensive knowledge base to provide self-help resources for customers'],
        ['subject' => 'Perform user acceptance testing for mobile app', 'description' => 'Engage users to test the mobile app and provide feedback on its usability and functionality'],
        ['subject' => 'Design promotional merchandise', 'description' => 'Create branded merchandise to promote the company and its products'],
        ['subject' => 'Implement content management system (CMS)', 'description' => 'Integrate a CMS to streamline content creation and management processes'],
        ['subject' => 'Conduct user interviews', 'description' => 'Conduct one-on-one interviews with users to gain insights and validate assumptions'],
        ['subject' => 'Design product user manuals', 'description' => 'Create user-friendly manuals to guide customers on using the product'],
        ['subject' => 'Perform system scalability testing', 'description' => 'Assess the system\'s scalability and performance under high user loads'],
        ['subject' => 'Optimize website for better conversion funnels', 'description' => 'Analyze and optimize the website to improve the flow of conversions'],
        ['subject' => 'Develop customer referral program', 'description' => 'Create a program to incentivize customers to refer new customers'],
        ['subject' => 'Conduct usability testing on mobile app', 'description' => 'Evaluate the mobile app\'s usability and identify areas for improvement'],
        ['subject' => 'Design product packaging inserts', 'description' => 'Create informative and promotional inserts for product packaging'],
        ['subject' => 'Implement social media listening tools', 'description' => 'Integrate tools to monitor and analyze brand mentions and customer sentiment on social media'],
        ['subject' => 'Write system administrator documentation', 'description' => 'Prepare detailed documentation to guide system administrators in managing the system'],
        ['subject' => 'Conduct competitor pricing analysis', 'description' => 'Research and analyze competitor pricing strategies to inform pricing decisions'],
        ['subject' => 'Design website hero banners', 'description' => 'Create visually captivating hero banners for the website\'s main sections'],
        ['subject' => 'Develop customer feedback analysis framework', 'description' => 'Define a framework to analyze and interpret customer feedback data'],
        ['subject' => 'Perform system security audit', 'description' => 'Assess the system\'s security measures and identify potential vulnerabilities'],
        ['subject' => 'Optimize website for faster page load times', 'description' => 'Improve website performance by optimizing page load times'],
        ['subject' => 'Develop onboarding materials for new employees', 'description' => 'Create resources to help new employees understand the company and their role'],
        ['subject' => 'Conduct user testing on mobile app', 'description' => 'Engage users to test the mobile app\'s functionality and provide feedback'],
        ['subject' => 'Design product packaging prototypes', 'description' => 'Produce physical prototypes of product packaging to evaluate their aesthetics and functionality'],
        ['subject' => 'Implement user feedback collection mechanism', 'description' => 'Establish a system for collecting and analyzing user feedback on an ongoing basis'],
        ['subject' => 'Create sales presentation materials', 'description' => 'Design visually compelling materials to support sales presentations'],
        ['subject' => 'Perform system error handling and bug fixing', 'description' => 'Identify and resolve errors and bugs in the system'],
        ['subject' => 'Prepare marketing campaign performance report', 'description' => 'Analyze the results of marketing campaigns and generate a performance report'],
        ['subject' => 'Design website color scheme and visual identity', 'description' => 'Define the color scheme and visual elements that represent the brand'],
        ['subject' => 'Develop customer segmentation strategy', 'description' => 'Segment the customer base to target specific groups with tailored marketing initiatives'],
        ['subject' => 'Conduct user feedback surveys', 'description' => 'Distribute surveys to gather feedback and insights from users'],
        ['subject' => 'Design product user onboarding process', 'description' => 'Create a seamless onboarding process to familiarize new users with the product'],
        ['subject' => 'Perform system load testing', 'description' => 'Simulate high user loads to evaluate the system\'s performance and stability'],
        ['subject' => 'Optimize website for better user engagement', 'description' => 'Implement strategies to increase user engagement and interaction on the website'],
        ['subject' => 'Develop customer journey mapping', 'description' => 'Visualize and understand the customer journey to improve the overall experience'],
        ['subject' => 'Conduct market segmentation analysis', 'description' => 'Analyze the market to identify distinct segments and their characteristics'],
        ['subject' => 'Design website layout and information architecture', 'description' => 'Create a well-structured and intuitive layout for the website'],
        ['subject' => 'Implement customer feedback response system', 'description' => 'Establish a process for promptly responding to customer feedback and addressing their concerns'],
        ['subject' => 'Create product demo videos', 'description' => 'Produce engaging videos demonstrating the features and benefits of the product'],
        ['subject' => 'Perform system performance optimization', 'description' => 'Identify and implement optimizations to enhance the system\'s performance'],
        ['subject' => 'Prepare competitor analysis report', 'description' => 'Analyze competitors\' strengths, weaknesses, and market positioning'],
        ['subject' => 'Design website call-to-action (CTA) buttons', 'description' => 'Create visually compelling CTAs to prompt users to take desired actions'],
        ['subject' => 'Develop customer engagement strategies', 'description' => 'Plan and implement initiatives to foster meaningful engagement with customers'],
        ['subject' => 'Conduct user surveys for product feedback', 'description' => 'Gather feedback from users to understand their experiences and suggestions'],
        ['subject' => 'Design product user interface mockups', 'description' => 'Create visual mockups of the user interface to visualize the final product'],
        ['subject' => 'Implement email automation workflows', 'description' => 'Set up automated email workflows for personalized communication with customers'],
        ['subject' => 'Write system user documentation', 'description' => 'Prepare user-friendly documentation to guide users in navigating the system'],
        ['subject' => 'Perform customer churn analysis', 'description' => 'Analyze customer behavior to identify factors contributing to churn'],
        ['subject' => 'Optimize website for better search engine visibility', 'description' => 'Implement SEO techniques to improve the website\'s search engine rankings'],
        ['subject' => 'Develop employee performance evaluation system', 'description' => 'Create a framework for evaluating and providing feedback on employee performance'],
        ['subject' => 'Conduct user acceptance testing for website', 'description' => 'Engage users to test the website and provide feedback on its functionality and usability'],
        ['subject' => 'Design product labels and packaging artwork', 'description' => 'Create visually appealing labels and artwork for product packaging'],
        ['subject' => 'Implement social media scheduling and publishing', 'description' => 'Set up a system to schedule and publish social media posts'],
        ['subject' => 'Write technical documentation for API integration', 'description' => 'Create comprehensive documentation to guide developers in integrating with the system\'s API'],
        ['subject' => 'Conduct market research for new product development', 'description' => 'Gather insights on market trends and customer needs to inform new product development'],
        ['subject' => 'Design website header and navigation menu', 'description' => 'Create an attractive header and user-friendly navigation menu for the website'],
        ['subject' => 'Develop customer feedback loop process', 'description' => 'Establish a process for continuously collecting, analyzing, and acting upon customer feedback'],
        ['subject' => 'Perform system database optimization', 'description' => 'Optimize the database structure and queries for improved system performance'],
        ['subject' => 'Optimize website for mobile devices', 'description' => 'Ensure the website is fully responsive and optimized for mobile browsing'],
        ['subject' => 'Develop employee training materials', 'description' => 'Create training materials to educate employees on various aspects of the business'],
        ['subject' => 'Conduct user testing for website usability', 'description' => 'Engage users to test the website\'s usability and identify areas for improvement'],
        ['subject' => 'Design product packaging prototypes', 'description' => 'Produce physical prototypes of product packaging to evaluate their aesthetics and functionality'],
        ['subject' => 'Implement user feedback collection mechanism', 'description' => 'Establish a system for collecting and analyzing user feedback on an ongoing basis'],
        ['subject' => 'Create sales presentation materials', 'description' => 'Design visually compelling materials to support sales presentations'],
        ['subject' => 'Perform system error handling and bug fixing', 'description' => 'Identify and resolve errors and bugs in the system'],
        ['subject' => 'Prepare marketing campaign performance report', 'description' => 'Analyze the results of marketing campaigns and generate a performance report'],
        ['subject' => 'Design website color scheme and visual identity', 'description' => 'Define the color scheme and visual elements that represent the brand'],
        ['subject' => 'Develop customer segmentation strategy', 'description' => 'Segment the customer base to target specific groups with tailored marketing initiatives'],
        ['subject' => 'Conduct user feedback surveys', 'description' => 'Distribute surveys to gather feedback and insights from users'],
        ['subject' => 'Design product user onboarding process', 'description' => 'Create a seamless onboarding process to familiarize new users with the product'],
        ['subject' => 'Perform system load testing', 'description' => 'Simulate high user loads to evaluate the system\'s performance and stability'],
        ['subject' => 'Optimize website for better user engagement', 'description' => 'Implement strategies to increase user engagement and interaction on the website'],
        ['subject' => 'Develop customer journey mapping', 'description' => 'Visualize and understand the customer journey to improve the overall experience'],
        ['subject' => 'Conduct market segmentation analysis', 'description' => 'Analyze the market to identify distinct segments and their characteristics'],
        ['subject' => 'Design website layout and information architecture', 'description' => 'Create a well-structured and intuitive layout for the website'],
        ['subject' => 'Implement customer feedback response system', 'description' => 'Establish a process for promptly responding to customer feedback and addressing their concerns'],
        ['subject' => 'Create product demo videos', 'description' => 'Produce engaging videos demonstrating the features and benefits of the product'],
        ['subject' => 'Perform system performance optimization', 'description' => 'Identify and implement optimizations to enhance the system\'s performance'],
        ['subject' => 'Prepare competitor analysis report', 'description' => 'Analyze competitors\' strengths, weaknesses, and market positioning'],
        ['subject' => 'Design website call-to-action (CTA) buttons', 'description' => 'Create visually compelling CTAs to prompt users to take desired actions'],
        ['subject' => 'Develop customer engagement strategies', 'description' => 'Plan and implement initiatives to foster meaningful engagement with customers'],
        ['subject' => 'Conduct user surveys for product feedback', 'description' => 'Gather feedback from users to understand their experiences and suggestions'],
        ['subject' => 'Design product user interface mockups', 'description' => 'Create visual mockups of the user interface to visualize the final product'],
        ['subject' => 'Implement email automation workflows', 'description' => 'Set up automated email workflows for personalized communication with customers'],
        ['subject' => 'Write system user documentation', 'description' => 'Prepare user-friendly documentation to guide users in navigating the system'],
        ['subject' => 'Perform customer churn analysis', 'description' => 'Analyze customer behavior to identify factors contributing to churn'],
        ['subject' => 'Optimize website for better search engine visibility', 'description' => 'Implement SEO techniques to improve the website\'s search engine rankings'],
        ['subject' => 'Develop employee performance evaluation system', 'description' => 'Create a framework for evaluating and providing feedback on employee performance'],
        ['subject' => 'Conduct user acceptance testing for website', 'description' => 'Engage users to test the website and provide feedback on its functionality and usability'],
        ['subject' => 'Design product labels and packaging artwork', 'description' => 'Create visually appealing labels and artwork for product packaging']
    ];

    public array $comments = [
        ['comment' => '<p>Great progress on this task. Keep up the good work!</p>'],
        ['comment' => '<p>I have a few suggestions to improve this task. Let\'s discuss them in the next meeting.</p>'],
        ['comment' => '<p>This task is taking longer than expected. Are there any blockers?</p>'],
        ['comment' => '<p>I just completed my part of the task. Waiting for others to finish their work.</p>'],
        ['comment' => '<p>Let\'s prioritize this task as it is critical for the project\'s success.</p>'],
        ['comment' => '<p>Good job on completing this task ahead of schedule!</p>'],
        ['comment' => '<p>I encountered a technical issue while working on this task. Need assistance.</p>'],
        ['comment' => '<p>I\'m stuck on this task. Can someone help me out?</p>'],
        ['comment' => '<p>We need to allocate more resources to this task to meet the deadline.</p>'],
        ['comment' => '<p>I suggest breaking down this task into smaller subtasks for better tracking.</p>'],
        ['comment' => '<p>This task seems redundant. Can we eliminate it to save time and effort?</p>'],
        ['comment' => '<p>The requirements for this task have changed. We need to update our approach.</p>'],
        ['comment' => '<p>I need more information to proceed with this task.</p>'],
        ['comment' => '<p>Let\'s set up a meeting to discuss the progress of this task.</p>'],
        ['comment' => '<p>We should document the steps involved in completing this task for future reference.</p>'],
        ['comment' => '<p>I have some concerns about the feasibility of this task.</p>'],
        ['comment' => '<p>This task is critical for the upcoming client presentation. Let\'s give it high priority.</p>'],
        ['comment' => '<p>The deadline for this task needs to be extended due to unexpected delays.</p>'],
        ['comment' => '<p>I propose assigning additional team members to this task to speed up the progress.</p>'],
        ['comment' => '<p>The quality of the output for this task needs improvement.</p>'],
        ['comment' => '<p>Let\'s allocate some time for research and brainstorming before starting this task.</p>'],
        ['comment' => '<p>This task requires further testing and validation before marking it as complete.</p>'],
        ['comment' => '<p>I found a more efficient way to accomplish this task. Shall I proceed with the changes?</p>'],
        ['comment' => '<p>I\'m facing a resource constraint that is affecting the progress of this task.</p>'],
        ['comment' => '<p>We should involve the client in the decision-making process for this task.</p>'],
        ['comment' => '<p>I recommend adding this task to the backlog for future iterations.</p>'],
        ['comment' => '<p>The scope of this task seems to be expanding. We need to revisit the estimates.</p>'],
        ['comment' => '<p>I have successfully completed my part of the task.</p>'],
        ['comment' => '<p>This task is blocking the progress of other dependent tasks.</p>'],
        ['comment' => '<p>Let\'s break down this task into milestones and track the progress accordingly.</p>'],
        ['comment' => '<p>I\'m impressed with the teamwork exhibited on this task.</p>'],
        ['comment' => '<p>The requirements for this task are unclear. We need to seek clarification.</p>'],
        ['comment' => '<p>We should archive this task as it is no longer relevant to the project.</p>'],
        ['comment' => '<p>I\'m experiencing some technical difficulties while working on this task.</p>'],
        ['comment' => '<p>Let\'s conduct a retrospective to analyze the challenges faced during this task.</p>'],
        ['comment' => '<p>We need to reassign this task to another team member due to their expertise.</p>'],
        ['comment' => '<p>This task can be automated to save time and effort.</p>'],
        ['comment' => '<p>I recommend documenting the lessons learned from this task for future projects.</p>'],
        ['comment' => '<p>We should create a knowledge base entry for this task to share insights.</p>'],
        ['comment' => '<p>I\'m unable to reproduce the issue mentioned in this task. Need more details.</p>'],
        ['comment' => '<p>This task has dependencies on external factors. Let\'s coordinate with the respective teams.</p>'],
        ['comment' => '<p>The estimated effort for this task was underestimated. We need to adjust the timeline.</p>'],
        ['comment' => '<p>Let\'s celebrate the successful completion of this task!</p>']
    ];

}
