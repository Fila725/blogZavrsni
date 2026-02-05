-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 05, 2026 at 06:55 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`) VALUES
(2, 'Sport', 'sport', '2026-02-05 18:49:34'),
(3, 'Politics', 'politics', '2026-02-05 21:22:39'),
(4, 'Technology', 'technology', '2026-02-05 21:22:53'),
(5, 'Business', 'business', '2026-02-05 21:23:01'),
(6, 'World News', 'world-news', '2026-02-05 21:23:11');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(240) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` mediumtext NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `category_id`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `status`, `created_at`, `updated_at`) VALUES
(9, 2, 2, 'Local Team Wins in Last-Minute Thriller', 'local-team-wins-in-last-minute-thriller', 'Fans went wild as the winning goal was scored in the final moments of the match.', 'In one of the most dramatic matches of the season, the local team secured a last-minute victory that left supporters in shock. The game was tightly contested, with both teams creating several scoring opportunities. The winning goal came after a fast counterattack that caught the opposition off guard. Coaches praised the team’s determination and fighting spirit.', '1770326033_69850811e8bf0.jpg', 'published', '2026-02-05 21:13:53', '2026-02-05 21:13:53'),
(10, 2, 2, 'Star Athlete Breaks Record in Historic Performance', 'star-athlete-breaks-record-in-historic-performance', 'Fans went wild as the winning goal was scored in the final moments of the match.', 'In one of the most dramatic matches of the season, the local team secured a last-minute victory that left supporters in shock. The game was tightly contested, with both teams creating several scoring opportunities. The winning goal came after a fast counterattack that caught the opposition off guard. Coaches praised the team’s determination and fighting spirit.', '1770326389_6985097546bde.jpg', 'published', '2026-02-05 21:19:49', '2026-02-05 21:19:49'),
(11, 2, 2, 'Coach Announces New Strategy Ahead of Tournament', 'coach-announces-new-strategy-ahead-of-tournament', 'The team is preparing a new game plan as they approach an important tournament.', 'The head coach announced that the team will be using a new strategy ahead of the upcoming tournament. The approach focuses on stronger defense, faster passing, and more aggressive attacking plays. Players have shown positive reactions during training sessions, and fans are hopeful that the changes will improve performance. The tournament begins next week and expectations are high.', '1770326405_69850985ace81.jpg', 'published', '2026-02-05 21:20:05', '2026-02-05 21:20:48'),
(12, 2, 2, 'Championship Final Expected to Draw Record Crowds', 'championship-final-expected-to-draw-record-crowds', 'Officials say the stadium may reach maximum capacity as excitement builds.', 'The championship final is expected to attract one of the largest crowds seen in recent years. Ticket sales have already surpassed predictions, and thousands of fans are traveling from different regions to attend. Organizers are increasing security and transportation support to manage the crowd. Experts predict the match will be intense, with both teams performing strongly throughout the season.', '1770326474_698509ca4d20b.jpg', 'published', '2026-02-05 21:21:14', '2026-02-05 21:21:14'),
(14, 2, 3, 'Government Announces New Economic Policy Changes', 'government-announces-new-economic-policy-changes', 'Officials revealed new policies aimed at improving the economy and reducing inflation.', 'The government has announced a new set of economic policy changes intended to boost national growth and reduce inflation. According to officials, the plan includes tax adjustments, increased support for small businesses, and new investment projects. Supporters say the policies could strengthen the economy, while critics argue they may not solve long-term issues. The announcement has sparked major debate across political circles.', '1770326690_69850aa2acf55.jpg', 'published', '2026-02-05 21:24:50', '2026-02-05 21:24:50'),
(15, 2, 3, 'Election Campaigns Heat Up Across the Country', 'election-campaigns-heat-up-across-the-country', 'Political parties are increasing their efforts as the election date approaches.', 'As the national election draws closer, political parties are ramping up their campaigns with rallies, speeches, and public appearances. Candidates are focusing on key issues such as employment, healthcare, and education. Citizens are paying close attention as polls show shifting public opinion. Political experts believe the election could be one of the most competitive in recent history.', '1770326796_69850b0ccfcf2.jpg', 'published', '2026-02-05 21:26:36', '2026-02-05 21:26:36'),
(16, 2, 3, 'Parliament Debates New National Security Bill', 'parliament-debates-new-national-security-bill', 'Lawmakers are divided over a bill that could change national security policies.', 'Parliament held a heated discussion today regarding a proposed national security bill. Supporters claim it will improve public safety and strengthen law enforcement powers. Opponents argue it may limit personal freedoms and increase government control. Citizens are watching closely as the debate continues. A final vote is expected in the coming days.', '1770326913_69850b81468a0.jpg', 'published', '2026-02-05 21:28:33', '2026-02-05 21:28:33'),
(17, 2, 3, 'International Leaders Meet for Peace and Trade Talks', 'international-leaders-meet-for-peace-and-trade-talks', 'Global leaders gathered today to discuss peace agreements and economic cooperation.', 'World leaders met today at an international summit to discuss key global issues, including trade, climate change, and regional conflict. The meeting focused on building stronger relationships and reducing tensions between nations. Officials say the talks were productive, though no major agreements have been finalized yet. Another meeting is expected next month.', '1770327029_69850bf51e15c.jpg', 'published', '2026-02-05 21:30:29', '2026-02-05 21:30:29'),
(18, 2, 4, 'New Smartphone Launch Promises Better Battery and Camera', 'new-smartphone-launch-promises-better-battery-and-camera', 'A major tech company has announced a phone with upgraded features and faster performance.', 'A new smartphone has been introduced featuring improved battery life, a stronger camera system, and faster processing speed. Early reviews suggest the device could compete with other top brands in the market. The phone also includes enhanced security features and new AI tools for daily use. Customers are already lining up for pre-orders.', '1770327236_69850cc4b4a31.jpg', 'published', '2026-02-05 21:33:56', '2026-02-05 21:33:56'),
(19, 2, 4, 'Artificial Intelligence Tools Are Changing Online Journalism', 'artificial-intelligence-tools-are-changing-online-journalism', 'News companies are using AI to speed up reporting and improve content quality.', 'Artificial intelligence is rapidly becoming part of modern journalism. News organizations are now using AI to summarize stories, detect breaking news trends, and even assist with writing headlines. While some experts see AI as a helpful tool, others worry about misinformation and loss of human creativity. The debate continues as technology evolves.', '1770327260_69850cdc25f5d.jpg', 'published', '2026-02-05 21:34:20', '2026-02-05 21:34:20'),
(20, 2, 4, 'Cybersecurity Experts Warn of Rising Online Threats', 'cybersecurity-experts-warn-of-rising-online-threats', 'Hackers are targeting users with phishing attacks and fake websites.', 'Cybersecurity experts have warned that online threats are increasing globally. Hackers are using phishing emails, fake apps, and scam websites to steal personal information. Officials advise users to use strong passwords, enable two-factor authentication, and avoid clicking unknown links. Businesses are also being encouraged to upgrade security systems to prevent major data breaches.', '1770327278_69850cee1c082.jpg', 'published', '2026-02-05 21:34:38', '2026-02-05 21:34:38'),
(21, 2, 4, 'Social Media Platforms Roll Out New Privacy Features', 'social-media-platforms-roll-out-new-privacy-features', 'New settings are being added to give users more control over their data.', 'Several major social media platforms have introduced new privacy features aimed at improving user control. These updates allow people to manage who can view their posts, limit tracking, and control targeted advertisements. Privacy advocates say the move is a step in the right direction, though they continue to call for stricter regulations. The new features are being rolled out gradually.', '1770327312_69850d101a358.jpg', 'published', '2026-02-05 21:35:12', '2026-02-05 21:35:12'),
(22, 2, 5, 'Stock Market Sees Strong Growth After Investor Optimism', 'stock-market-sees-strong-growth-after-investor-optimism', 'Major markets rose today as investors reacted positively to new reports.', 'The stock market experienced a strong rise today as investor confidence increased. Analysts say the growth was driven by positive earnings reports and improved economic predictions. Technology and banking stocks showed the biggest gains. Experts warn, however, that markets remain sensitive to global events and inflation changes.', '1770327542_69850df6c432d.jpg', 'published', '2026-02-05 21:39:02', '2026-02-05 21:39:02'),
(23, 2, 5, 'Small Businesses Expand as Online Shopping Increases', 'small-businesses-expand-as-online-shopping-increases', 'Many small companies are benefiting from digital sales and delivery services.', 'Small businesses are experiencing growth as more consumers turn to online shopping. Entrepreneurs are using social media and e-commerce platforms to reach new customers. Delivery services and digital payments have also made it easier for small companies to compete. Experts say the trend could continue as technology becomes more accessible.', '1770327559_69850e076186c.jpg', 'published', '2026-02-05 21:39:19', '2026-02-05 21:39:19'),
(24, 2, 5, 'Fuel Prices Rise Again, Affecting Transportation Costs', 'fuel-prices-rise-again-affecting-transportation-costs', 'Higher fuel prices are creating challenges for businesses and commuters.', 'Fuel prices have increased once again, putting pressure on transportation companies and everyday commuters. Many businesses are reporting higher delivery costs, which could lead to increased product prices. Government officials say they are monitoring the situation closely. Analysts suggest global oil supply and demand remain major factors in the rising prices.', '1770327577_69850e191f137.jpg', 'published', '2026-02-05 21:39:37', '2026-02-05 21:39:37'),
(25, 2, 5, 'Major Company Announces Thousands of New Job Openings', 'major-company-announces-thousands-of-new-job-openings', 'A leading company is expanding operations and hiring more workers.', 'A major corporation has announced plans to hire thousands of new employees over the next year. The company stated that growth in customer demand has increased the need for workers across multiple departments. Job seekers are welcoming the announcement, while economists say this could have a positive impact on local economies. Recruitment is expected to begin soon.', '1770327593_69850e2990324.jpg', 'published', '2026-02-05 21:39:53', '2026-02-05 21:39:53'),
(26, 2, 6, 'Powerful Storm Causes Damage in Coastal Cities', 'powerful-storm-causes-damage-in-coastal-cities', 'Heavy rain and strong winds have left many homes and roads damaged.', 'A powerful storm hit coastal cities today, causing major damage to buildings and transportation systems. Emergency teams are working to restore power and assist affected families. Officials have advised residents to remain indoors until conditions improve. Weather experts say more storms may occur in the coming days.', '1770327627_69850e4bd66cf.jpg', 'published', '2026-02-05 21:40:27', '2026-02-05 21:40:27'),
(27, 2, 6, 'Global Health Experts Monitor New Virus Outbreak Reports', 'global-health-experts-monitor-new-virus-outbreak-reports', 'Authorities are investigating reports of a new virus spreading in multiple regions.', 'Health experts are monitoring reports of a new virus outbreak that has been detected in several areas. Officials say early investigations are underway to understand symptoms and transmission rates. While the situation is being controlled, people are advised to follow safety measures such as hygiene and avoiding crowded spaces. Updates are expected as more information becomes available.', '1770327645_69850e5d5260f.jpg', 'published', '2026-02-05 21:40:45', '2026-02-05 21:40:45'),
(28, 2, 6, 'International Flights Resume After Travel Restrictions Lifted', 'international-flights-resume-after-travel-restrictions-lifted', 'Airports are seeing increased activity as restrictions are removed.', 'nternational flights have resumed in several countries after travel restrictions were lifted. Airlines are reporting higher passenger numbers, and tourism businesses are welcoming the return of travelers. Authorities continue to advise travelers to follow safety guidelines. Many believe the travel industry may recover strongly in the coming months.', '1770327661_69850e6d3df0f.jpg', 'published', '2026-02-05 21:41:01', '2026-02-05 21:41:01'),
(29, 2, 6, 'Scientists Report Major Progress in Renewable Energy Research', 'scientists-report-major-progress-in-renewable-energy-research', 'New technology could improve solar and wind power efficiency.', 'Scientists have announced a major breakthrough in renewable energy research that could significantly improve efficiency in solar and wind power. Experts say the new development may reduce costs and increase energy production worldwide. Environmental groups have praised the progress, calling it an important step toward fighting climate change. More testing and development is expected soon.', '1770327687_69850e8740a10.jpg', 'published', '2026-02-05 21:41:27', '2026-02-05 21:41:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(190) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','author') NOT NULL DEFAULT 'author',
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `bio`, `created_at`) VALUES
(2, 'Filip', 'filiptomic@email.com', '$2y$10$FgiwFxpx5yNa2GKTSHZSaOhZFQ5BQb3m1TU3YJwgVR1TRmT08X3Mi', 'admin', NULL, '2026-02-05 18:36:34'),
(3, 'Leon', 'leontomic@email.com', '$2y$10$sewc9Zcb8MwUzZyxKvqIQuAPSZtqNppg7OxWGmUC7MF8d23loFFbG', 'author', NULL, '2026-02-05 20:23:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
