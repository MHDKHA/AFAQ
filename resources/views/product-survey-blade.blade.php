{{-- Product Focus 2025 Survey of the Product Management Profession --}}
@extends('layouts.main')

@section('title', 'Product Focus 2025 Survey of the Product Management Profession')

@section('styles')
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .header {
            background-color: #000;
            color: #fff;
            padding: 20px 0;
            margin-bottom: 30px;
        }
        .footer {
            background-color: #263c41;
            color: #fff;
            padding: 20px 0;
            margin-top: 30px;
        }
        .logo {
            max-width: 180px;
        }
        h1, h2, h3 {
            color: #263c41;
        }
        .year {
            color: #57aa5a;
            font-size: 4rem;
            font-weight: bold;
        }
        .highlight-section {
            margin: 30px 0;
        }
        .highlight-number {
            font-size: 5rem;
            color: #57aa5a;
            font-weight: bold;
        }
        .highlight-box {
            background-color: #263c41;
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
        }
        .chart-container {
            margin: 25px 0;
        }
        .content-section {
            margin-bottom: 40px;
        }
        .stat-card {
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .icon-box {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .icon-box i {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #263c41;
        }
        .contact-info {
            text-align: center;
            margin: 30px 0;
        }
        .contact-info i {
            color: white;
            margin-right: 10px;
        }
        .tool-logo {
            height: 40px;
            margin-right: 10px;
        }
    </style>
@endsection

@section('content')
    {{-- Cover Page --}}
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-9">
                <div class="year">2025</div>
                <h1 class="display-4">Survey of the Product <br>Management Profession</h1>
            </div>
            <div class="col-md-3">
                <img src="{{ asset('images/product-focus-logo.png') }}" alt="Product Focus" class="logo img-fluid">
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div class="icon-grid">
                    {{-- Icons shown on the cover page --}}
                    <img src="{{ asset('images/product-icons.svg') }}" alt="Product Management Icons" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    {{-- Table of Contents --}}
    <div class="container content-section">
        <div class="row">
            <div class="col-md-9">
                <h2>Table of contents</h2>
                <div class="toc">
                    <div class="row">
                        <div class="col-md-6">
                            <p><a href="#introduction">Introduction</a> <span class="dotted-line"></span> <span class="page-num">3</span></p>
                            <p><a href="#highlights">Highlights</a> <span class="dotted-line"></span> <span class="page-num">4</span></p>
                            <p><a href="#what-we-get-paid">What we get paid</a> <span class="dotted-line"></span> <span class="page-num">5</span></p>
                            <p><a href="#our-role-and-background">Our role and background</a> <span class="dotted-line"></span> <span class="page-num">7</span></p>
                            <p><a href="#our-products-and-customers">Our products and customers</a> <span class="dotted-line"></span> <span class="page-num">10</span></p>
                            <p><a href="#how-we-develop-products">How we develop products</a> <span class="dotted-line"></span> <span class="page-num">11</span></p>
                            <p><a href="#what-we-value">What we value</a> <span class="dotted-line"></span> <span class="page-num">12</span></p>
                            <p><a href="#leadership-role">A leadership role?</a> <span class="dotted-line"></span> <span class="page-num">12</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><a href="#ai-tools">AI tools</a> <span class="dotted-line"></span> <span class="page-num">14</span></p>
                            <p><a href="#how-we-spend-our-time">How we spend our time</a> <span class="dotted-line"></span> <span class="page-num">15</span></p>
                            <p><a href="#tools-we-use">The tools we use</a> <span class="dotted-line"></span> <span class="page-num">16</span></p>
                            <p><a href="#big-issues">The big issues we face</a> <span class="dotted-line"></span> <span class="page-num">18</span></p>
                            <p><a href="#important-skills">Which skills will be important?</a> <span class="dotted-line"></span> <span class="page-num">19</span></p>
                            <p><a href="#product-activities-framework">Product Activities Framework</a> <span class="dotted-line"></span> <span class="page-num">20</span></p>
                            <p><a href="#stop-firefighting">Stop firefighting</a> <span class="dotted-line"></span> <span class="page-num">21</span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <img src="{{ asset('images/product-focus-logo.png') }}" alt="Product Focus" class="logo img-fluid">
            </div>
        </div>
    </div>

    {{-- Introduction --}}
    <div id="introduction" class="container content-section">
        <div class="row">
            <div class="col-md-9">
                <h2>Introduction</h2>
                <p>Product Focus is a European leader in product management training.</p>
                <p>We conduct an annual survey, asking product professionals about their roles, salaries, daily activities, and major challenges. This includes product people at every level, with job titles ranging from Junior Product Manager to Chief Product Officer, as well as related roles such as Product Marketers and Product Owners.</p>
                <p>This year, 532 people from 47 countries participated in the survey. Most respondents were from the UK and Europe (82%), but we also had a significant number from the US (10%) and other regions (8%). All responses in this report were collected between November 2024 and January 2025.</p>
                <p>These insights provide a benchmark to help you better understand your role and product management within your company.</p>
                <p>In many areas, the survey results reflect common practices, not necessarily best practices. You can learn about best practices by signing up for our <a href="#">free resources</a> or attending one of our <a href="#">training courses</a>.</p>
            </div>
            <div class="col-md-3">
                <img src="{{ asset('images/world-map.png') }}" alt="Respondent distribution" class="img-fluid">
                <div class="text-center mt-3">
                    <div class="stat-box">
                        <span class="stat-label">Europe</span>
                        <span class="stat-value">82%</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-label">US</span>
                        <span class="stat-value">10%</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-label">ROW</span>
                        <span class="stat-value">8%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Highlights --}}
    <div id="highlights" class="container content-section">
        <div class="row">
            <div class="col-md-12">
                <h2>Highlights</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="highlight-box">
                    <h3 class="display-4">01</h3>
                    <h4>Customer engagement vs. firefighting</h4>
                    <p>73% of respondents feel they don't spend enough time engaging with customers. Among these, 58% cite "too much firefighting" as a major issue. Better prioritization, processes, clearer roles, and more resourcing may help.</p>
                </div>
                <div class="highlight-box">
                    <h3 class="display-4">03</h3>
                    <h4>Deeply human skills prevail</h4>
                    <p>While AI dominates future forecasts, product managers are starting to emphasize human capabilities – empathy, communication, and strategy – as increasingly essential. PMs will become expert AI collaborators.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="highlight-box">
                    <h3 class="display-4">02</h3>
                    <h4>AI is the most recommended tool</h4>
                    <p>Given AI tools top the recommended tools list and 95% of respondents express interest in learning more about AI, organizations might benefit from formal AI training programs specifically for product management.</p>
                </div>
                <div class="highlight-box">
                    <h3 class="display-4">04</h3>
                    <h4>Strategic positioning of product management</h4>
                    <p>Many companies now recognize product management as a strategic function, but others still face organizational hurdles by treating it as primarily operational – this limits their strategic and innovation potential.</p>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <p>The demand for in-person training continues to grow as professionals value human connection and collaborative learning. Meanwhile, AI enthusiasm is surging—product managers actively seek ways to automate routine tasks while enhancing productivity. Yet core product management skills remain irreplaceable, particularly the human abilities to understand customer needs deeply and to create products that truly resonate.</p>
            </div>
        </div>
    </div>

    {{-- What we get paid --}}
    <div id="what-we-get-paid" class="container content-section">
        <div class="row">
            <div class="col-md-12">
                <h2>What we get paid</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="highlight-box">
                    <div class="highlight-number">2%</div>
                    <p>the average salary increase from 2024 to 2025 across all currencies</p>
                </div>
                <p>Salary inflation reduced from the previous year's increase of 6%. Note also: those paid in $ are on average paid more than those paid in £ or €.</p>
            </div>
            <div class="col-md-8">
                <div class="chart-container">
                    <h4>Average salaries</h4>
                    <img src="{{ asset('images/salary-chart.png') }}" alt="Average salaries chart" class="img-fluid">
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <h4>What's in the full package?</h4>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <h5>Head of, Director or VP</h5>
                    <img src="{{ asset('images/senior-package-chart.png') }}" alt="Senior package chart" class="img-fluid">
                </div>
                <div class="chart-container">
                    <h5>Product Manager</h5>
                    <img src="{{ asset('images/pm-package-chart.png') }}" alt="Product Manager package chart" class="img-fluid">
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <h5>Senior Product Manager</h5>
                    <img src="{{ asset('images/senior-pm-package-chart.png') }}" alt="Senior PM package chart" class="img-fluid">
                </div>
                <div class="chart-container">
                    <h5>Junior Product Manager</h5>
                    <img src="{{ asset('images/junior-pm-package-chart.png') }}" alt="Junior PM package chart" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    {{-- Continue with remaining sections... --}}
    <div id="our-role-and-background" class="container content-section">
        <div class="row">
            <div class="col-md-12">
                <h2>Current role and experience</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="highlight-box">
                    <div class="highlight-number">3</div>
                    <p>The average number of years respondents have been in their current role</p>
                </div>
                <p>About 18% of respondents have been in their current role for 5 or more years, about the same as last year.</p>
            </div>
            <div class="col-md-8">
                <div class="chart-container">
                    <h4>How long have you been in your current role?</h4>
                    <img src="{{ asset('images/current-role-chart.png') }}" alt="Current role chart" class="img-fluid">
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="highlight-box">
                    <div class="highlight-number">79%</div>
                    <p>of respondents had more than 4 years' total experience managing products</p>
                </div>
                <p>People build a career in product management. 36% have more than 10 years' experience managing products.</p>
            </div>
            <div class="col-md-8">
                <div class="chart-container">
                    <h4>How long have you worked in jobs managing products?</h4>
                    <img src="{{ asset('images/experience-chart.png') }}" alt="Experience chart" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    {{-- Our background --}}
    <div id="our-background" class="container content-section">
        <div class="row">
            <div class="col-md-12">
                <h2>Our background</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="highlight-box">
                    <div class="highlight-number">37%</div>
                    <p>of respondents say they have a balanced background</p>
                </div>
                <p>Half of product managers said they come from a technical background, about the same as last year.</p>
            </div>
            <div class="col-md-8">
                <div class="chart-container">
                    <h4>How would you describe your background?</h4>
                    <img src="{{ asset('images/background-chart.png') }}" alt="Background chart" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    {{-- Which industries do we work in? --}}
    <div id="industries" class="container content-section">
        <div class="row">
            <div class="col-md-12">
                <h2>Which industries do we work in?</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="highlight-box">
                    <div class="highlight-number">27%</div>
                    <p>of respondents work in IT Services</p>
                </div>
                <p>Product management is a great transferable skill, allowing you to work in many industries.</p>
            </div>
            <div class="col-md-8">
                <div class="chart-container">
                    <h4>Which industry do you work in?</h4>
                    <img src="{{ asset('images/industry-chart.png') }}" alt="Industry chart" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    {{-- Continue with remaining sections... --}}

    {{-- AI tools section --}}
    <div id="ai-tools" class="container content-section">
        <div class="row">
            <div class="col-md-12">
                <h2>AI tools?</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="highlight-box">
                    <div class="highlight-number">67%</div>
                    <p>of respondents say that AI tools have increased their productivity</p>
                </div>
                <p>Those citing productivity improvements is up by 15% from last year. Also, you told us that you want AI to handle repetitive tasks, provide quick yet reliable insights, and enable more informed decision-making — all while maintaining data security and trustworthiness.</p>
            </div>
            <div class="col-md-8">
                <div class="chart-container">
                    <h4>How has the use of AI tools influenced your overall productivity in product management tasks?</h4>
                    <img src="{{ asset('images/ai-productivity-chart.png') }}" alt="AI productivity chart" class="img-fluid">
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="highlight-box">
                    <div class="highlight-number">95%</div>
                    <p>of respondents would like to learn more about AI tools</p>
                </div>
                <p>We believe that Product Managers should have a good product management education, to effectively assess the accuracy and credibility of output from AI tools.</p>
            </div>
            <div class="col-md-8">
                <div class="chart-container">
                    <h4>How interested are you in learning about artificial intelligence (AI) tools to enhance your productivity in product management tasks?</h4>
                    <img src="{{ asset('images/ai-interest-chart.png') }}" alt="AI interest chart" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    {{-- Recommended tools section --}}
    <div id="tools-we-use" class="container content-section">
        <div class="row">
            <div class="col-md-12">
                <h2>Top 10 recommended tools</h2>
                <div class="chart-container">
                    <h4>Top 10 most-recommended tools</h4>
                    <img src="{{ asset('images/tools-chart.png') }}" alt="Recommended tools chart" class="img-fluid">
                </div>
                <p>We asked people to pick the top three tools that they would recommend to other product managers...</p>
                <p>ChatGPT has quickly become the most recommended tool, taking the number one spot away from Jira last year. Miro has climbed to number three, overtaking Confluence from that position last year.</p>
                <p>Office tools like Excel and PowerPoint continue to be popular, with the added benefit of AI being embedded natively as Copilot. Many other tools, including Jira and Miro, now integrate AI into their offerings to deliver additional value.</p>
                <p>In case you're not familiar with some of these tools, we briefly describe them on the next page.</p>
                <h4>The Future?</h4>
                <p>Think of the AI market like the electric power industry—soon all providers will deliver capable AI models just as utility companies provide consistent electrical service. The real difference will come from what companies build using this power—specialized tools and workflows for specific industries and tasks.</p>
                <p>We'll move from simply chatting with AI to having AI assistants that can think, plan, and handle complex work on their own. But these assistants won't replace humans—instead, they'll work alongside us, with people providing guidance and expertise while the AI handles the heavy lifting.</p>
            </div>
        </div>
    </div>

    {{-- Big issues section --}}
    <div id="big-issues" class="container content-section">
        <div class="row">
            <div class="col-md-12">
                <h2>The big issues we face</h2>
                <div class="chart-container">
                    <img src="{{ asset('images/issues-chart.png') }}" alt="Issues chart" class="img-fluid">
                </div>
                <h4>Survey findings</h4>
                <p>Our survey revealed key challenges that are shaping product management today. The findings highlight the top three areas of concern: Too much firefighting, lack of resource, and prioritization.</p>
                <h4>Firefighting: unplanned versus planned</h4>
                <p>Firefighting has emerged as your most significant challenge, with a 10% increase from last year. The constant pressure to address urgent issues prevents you from focusing on what matters most. Our survey revealed that only 22% of product managers dedicate most of their time to strategic activities, highlighting a troubling gap between where your time goes and where it creates the most value. This reactive cycle continues to be a source of frustration across the product management community.</p>
                <h4>Limited resources: a common struggle</h4>
                <p>Resource constraints continue to challenge product managers across the board. Insufficient headcount, budget limitations, inadequate tools, and lack of time (stolen by firefighting) all hinder your ability to execute effectively. The resulting tension between ambitious product goals and limited means creates a persistent frustration that directly impacts product outcomes and team morale.</p>
                <h4>Prioritization: making tough choices</h4>
                <p>Deciding what to build—and what not to build—remains one of your greatest challenges. The data shows product managers continue to struggle with prioritization amid competing stakeholder demands and shifting market conditions. This highlights the ongoing need for robust prioritization frameworks that can withstand organizational scrutiny while keeping products focused on delivering genuine customer value.</p>
                <h4>Conclusion: more time for strategic focus</h4>
                <p>The survey findings reveal a clear need for better organizational support for product managers. This means adequate resources, a culture that values strategic work alongside tactical needs, and practical prioritization tools. We offer proven approaches to tackle these challenges through our <a href="#">training courses</a> and <a href="#">resources</a>.</p>
            </div>
        </div>
    </div>

    {{-- Important skills section --}}
    <div id="important-skills" class="container content-section">
        <div class="row">
            <div class="col-md-12">
                <h2>Which skills will be important?</h2>
                <p>In the survey we asked respondents about the skills that they think will be most important for product managers in 2025?</p>
                <p>We analyzed hundreds of written comments to identify the most common skills mentioned. They fell into two roughly equal categories – 'soft' skills and 'hard' product management skills. Within each category, we've listed the top 10 key skills in rough order of number of mentions.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>'Soft' Skills</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Communication and influence</li>
                            <li class="list-group-item">Adaptability and flexibility</li>
                            <li class="list-group-item">Stakeholder management</li>
                            <li class="list-group-item">Prioritization and decision making</li>
                            <li class="list-group-item">Collaboration and leadership</li>
                            <li class="list-group-item">Strategic thinking and vision</li>
                            <li class="list-group-item">Empathy and customer focus</li>
                            <li class="list-group-item">Problem-solving and creativity</li>
                            <li class="list-group-item">Resilience and managing uncertainty</li>
                            <li class="list-group-item">Negotiation skills</li>
                        </ul>
                    </div>
                </div>
                <div class="quote-box mt-3">
                    <blockquote class="blockquote">
                        <p class="mb-0">"Soft skills are critical for product managers. Without direct authority, success depends on your ability to influence, persuade and inspire people."</p>
                    </blockquote>
                </div>
                <div class="quote-box mt-3">
                    <blockquote class="blockquote">
                        <p class="mb-0">"Most PMs I work with don't have any idea what strategy is or how to build or use it."</p>
                    </blockquote>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4>'Hard' skills</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">AI proficiency – understanding and use</li>
                            <li class="list-group-item">Data analysis and literacy</li>
                            <li class="list-group-item">Product strategy and roadmapping</li>
                            <li class="list-group-item">Market research and analysis</li>
                            <li class="list-group-item">Technical acumen</li>
                            <li class="list-group-item">Business case development</li>
                            <li class="list-group-item">Financial and business acumen</li>
                            <li class="list-group-item">Requirements gathering and discovery</li>
                            <li class="list-group-item">Project management</li>
                            <li class="list-group-item">Product marketing and positioning</li>
                        </ul>
                    </div>
                </div>
                <div class="quote-box mt-3">
                    <blockquote class="blockquote">
                        <p class="mb-0">"I wish my company understood how to do product management and product marketing correctly."</p>
                    </blockquote>
                </div>
                <div class="quote-box mt-3">
                    <blockquote class="blockquote">
                        <p class="mb-0">"AI will be a 'must' for product managers."</p>
                    </blockquote>
                </div>
            </div>
        </div>
    </div>

    {{-- Product Activities Framework --}}
    <div id="product-activities-framework" class="container content-section">
        <div class="row">
            <div class="col-md-12">
                <h2>Product Activities Framework</h2>
                <p>Clarifying and explaining what product management does is a key challenge for many product people.</p>
                <p>Our Product Activities Framework can help with this. It identifies all the product-related activities that need to take place in any company with products.</p>
                <p>Use it to sort out which product roles own each activity, to evangelize what product management does, and think about where you need to make improvements.</p>
                <p>You can download our infographic with a detailed description of each activity by signing up for our <a href="#">Toolbox</a> at our website.</p>
                <div class="framework-image text-center mt-4">
                    <img src="{{ asset('images/product-activities-framework.png') }}" alt="Product Activities Framework" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    {{-- Stop firefighting --}}
    <div id="stop-firefighting" class="container content-section">
        <div class="row">
            <div class="col-md-12">
                <h2>Stop firefighting</h2>
                <p>... and deliver world-class product management</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="service-box bg-info text-white p-4 rounded">
                    <h3>Public training courses</h3>
                    <ul class="list-unstyled">
                        <li><strong>Essentials:</strong> Product Management and Product Marketing</li>
                        <li><strong>Advanced:</strong> AI for Product Managers; Driving Product Growth</li>
                        <li><strong>Leading:</strong> Leading Product Management</li>
                        <li>Build the skills, tools, and confidence to excel in your role</li>
                        <li>Live online or in locations across Europe</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-box bg-success text-white p-4 rounded">
                    <h3>Private training for your team</h3>
                    <ul class="list-unstyled">
                        <li>Improve team performance with private training in-person or live online</li>
                        <li>Get the whole team using consistent best practice approaches</li>
                        <li>Use our online toolbox to access resources and tools when you need them</li>
                        <li>Customizable to your context for maximum relevancy and impact</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-box bg-primary text-white p-4 rounded">
                    <h3>Leadership support</h3>
                    <ul class="list-unstyled">
                        <li>Learn how to manage a product management function, department, or team</li>
                        <li>Coaching to assist leaders as they establish and improve their product management</li>
                        <li>Executive briefings to explain the value of product management to your senior team</li>
                        <li>Product Focus Catalyst service to help you turn training into business impact</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Contact/Final Section --}}
    <div class="container content-section">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h2>Learn best practices and improve performance with the European leaders</h2>
                <p class="mt-4">If you'd like to discuss product management training, or how we can support your product management function, please contact us:</p>
                <div class="contact-info">
                    <p><i class="fas fa-phone"></i> +44 (0) 207 099 5567</p>
                    <p><i class="fas fa-envelope"></i> info@productfocus.com</p>
                    <p><i class="fas fa-globe"></i> www.productfocus.com</p>
                </div>
                <div class="trustpilot-box mt-4">
                    <img src="{{ asset('images/trustpilot.png') }}" alt="Trustpilot score" class="img-fluid" style="max-width: 300px;">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // You can add any JavaScript needed for the page here
        $(document).ready(function() {
            // Initialize any charts or interactive elements
        });
    </script>
@endsection
