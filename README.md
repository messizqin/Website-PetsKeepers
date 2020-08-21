# Pets Keeping Website

<u>For pet keeping business to allow their users to book online</u>
<br />
<a href='https://github.com/Weilory/Website-PetsKeepers/blob/master/docs/develop.md'>Developing Notes</a>
<br /><br />
<h1>Requirements</h1>
<h3>development</h3>

* Apache MySQL
* PHP 7
* HTML 5

<h3>language</h3>

* English

<h3>requirements</h3>
* SVG Supported Browser
<hr />
<h1>Usage</h1>

<ol>
<h2>database</h2>

```sql
-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Aug 21, 2020 at 03:50 PM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `petkeepers`
--

-- --------------------------------------------------------

--
-- Table structure for table `dates`
--

CREATE TABLE `dates` (
  `id` int(4) NOT NULL,
  `user_id` int(4) NOT NULL,
  `booked` date NOT NULL,
  `sendin` date NOT NULL,
  `pickup` date NOT NULL,
  `duration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `id` int(4) NOT NULL,
  `date_id` int(4) NOT NULL,
  `category` varchar(50) NOT NULL,
  `petname` varchar(20) NOT NULL,
  `petage` int(11) NOT NULL,
  `petweight` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(63) CHARACTER SET ascii NOT NULL DEFAULT '',
  `data` text,
  `expire` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(4) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `activated` tinyint(2) NOT NULL,
  `token` varchar(100) DEFAULT NULL,
  `created_on` date NOT NULL,
  `admin` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dates`
--
ALTER TABLE `dates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expire` (`expire`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dates`
--
ALTER TABLE `dates`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;

```

<h2>Test</h2>
<li>Set up gmail for <a href="https://www.google.com/landing/2step/">two-step verification</a></li>
<li>Set up gmail <a href='https://support.google.com/accounts/answer/185833?hl=en'>app password</a></li>
<li>in php folder, open <b>credential.php</b>, enter your email address and the 16-digits token which is the app password of your gmail</li>
<h2>Session</h2>
<li>in php folder, open <b>sess.php</b>, similar to <b>db_connect.php</b>, edit information to your own database connection</li>
</ol>

