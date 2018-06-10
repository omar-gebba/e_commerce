-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 09, 2018 at 06:44 PM
-- Server version: 5.7.22-0ubuntu0.16.04.1
-- PHP Version: 7.0.30-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `omar`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `describtion` text,
  `parent` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) DEFAULT NULL,
  `visibility` tinyint(4) DEFAULT NULL,
  `allow_comments` tinyint(4) DEFAULT '0',
  `allow_ads` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `name`, `describtion`, `parent`, `ordering`, `visibility`, `allow_comments`, `allow_ads`) VALUES
(2, 'Hand Made', NULL, 0, 21, 0, NULL, 0),
(4, 'elect', NULL, 0, 2242, 0, NULL, 0),
(5, 'Toys', 'kidds toys', 0, 4324, 1, 1, 1),
(7, 'Guns', NULL, 0, 1, 1, NULL, 1),
(8, 'cars', NULL, 0, 1, 0, NULL, 0),
(10, 'Games', '', 0, 21, 0, 0, 0),
(12, 'Electronics', NULL, 4, 2, 0, NULL, 0),
(13, 'spider man', 'this is a spider  man', 5, 2, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comID` int(11) NOT NULL,
  `comment` text,
  `status` tinyint(4) DEFAULT NULL,
  `add_date` date DEFAULT NULL,
  `item_ID` int(11) DEFAULT NULL,
  `user_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comID`, `comment`, `status`, `add_date`, `item_ID`, `user_ID`) VALUES
(1, 'wow nice i phone ', 1, '2017-12-30', 1, 3),
(2, 'very cheap computer', 1, '2018-01-03', 5, 1),
(3, 'very good boiler', 1, '2018-01-08', 3, 1),
(4, 'very good boiler', 0, '2018-01-08', 3, 1),
(5, 'very good boiler', 0, '2018-01-08', 3, 1),
(6, 'good door', 0, '2018-01-08', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_ID` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `price` varchar(255) DEFAULT NULL,
  `add_date` date DEFAULT NULL,
  `country_made` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `rating` smallint(6) DEFAULT NULL,
  `aprove` smallint(6) DEFAULT '0',
  `cat_ID` int(11) DEFAULT NULL,
  `member_ID` int(11) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `item_img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_ID`, `name`, `description`, `price`, `add_date`, `country_made`, `image`, `status`, `rating`, `aprove`, `cat_ID`, `member_ID`, `tags`, `item_img`) VALUES
(1, 'i phone ', 'a nice iphone', '100', '2017-12-30', 'USA', NULL, '1', NULL, 1, 4, 1, NULL, ''),
(2, 'Door', 'wooden door', '30', '2017-12-30', 'Egypt', NULL, '3', NULL, 1, 2, 3, NULL, ''),
(3, 'Boiler', 'a good boiler', '10', '2018-01-02', 'China', NULL, '1', NULL, 1, 4, 20, NULL, ''),
(4, 'chair', 'metalic chair', '30', '2018-01-02', 'Egypt', NULL, '1', NULL, 0, 2, 3, 'chair,metalicchair', ''),
(5, 'computer', 'hp desctop', '500', '2018-01-03', 'USA', NULL, '1', NULL, 1, 12, 2, 'computers,computer,hp,labtop,desctop', ''),
(8, 'mouse', 'leaser mouse', '4', '2018-01-03', 'China', NULL, '1', NULL, 1, 12, 25, 'mouse, leasermouse, desktop, labtop', ''),
(9, 'head phones', 'good head phones', '10', '2018-01-03', 'Turkey', NULL, '1', NULL, 1, 12, 23, 'headPHones,CompuTErs,LABTopPP', ''),
(10, 'Machine Gun', 'A heavy Machine Gun', '1000', '2018-01-04', 'Rusa', NULL, '1', NULL, 0, 7, 1, 'Guns, machine gun, machine gun', ''),
(11, 'PES 4', 'fantastic pes 4', '200', '2018-01-07', 'Korea', NULL, '1', NULL, 1, 10, 1, 'pes, games, computergame', 'admin/uploads/items/643941822687596_1464504410299939_8738609648740750783_n.jpg'),
(12, 'a car', 'a good jeep car', '100000', '2018-01-10', 'Germany', NULL, '2', NULL, 1, 8, 26, 'car, cars, veichls', 'admin/uploads/items/319088126734095_1775969549121498_3019987320518838666_n.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `groupID` int(11) DEFAULT '0',
  `truststatus` int(11) DEFAULT '0',
  `regstatus` int(11) DEFAULT '0',
  `regdate` date DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `password`, `email`, `fullname`, `groupID`, `truststatus`, `regstatus`, `regdate`, `img`) VALUES
(1, 'omar', 'c984aed014aec7623a54f0591da07a85fd4b762d', 'omar@info.com.com', 'omar gebbaa', 1, 0, 1, '2017-09-23', 'uploads/avatars/omar.jpg'),
(2, 'kamal', 'c984aed014aec7623a54f0591da07a85fd4b762d', 'kamel@info.com', 'kamal kamal', 0, 0, 0, '2017-10-25', 'uploads/avatars/190_house.png'),
(3, 'sayed', 'c984aed014aec7623a54f0591da07a85fd4b762d', 'sayed@sdsd.com', 'sayd saad', 0, 0, 0, '2017-10-26', NULL),
(20, 'ibrahim', 'c984aed014aec7623a54f0591da07a85fd4b762d', 'ibra@ibra.com', 'ibra sayed', 0, 0, 1, '2017-10-25', NULL),
(23, 'rady', 'c984aed014aec7623a54f0591da07a85fd4b762d', 'rady@r.com', 'rady samy', 0, 0, 1, '2017-10-26', NULL),
(24, 'saleh', 'c984aed014aec7623a54f0591da07a85fd4b762d', 'saleh@m.com', 'saleh sayed', 0, 0, 0, '2017-12-06', NULL),
(25, 'gomaa', 'c984aed014aec7623a54f0591da07a85fd4b762d', 'goma@g.com', 'goma ahmed', 0, 0, 1, '2017-12-06', NULL),
(26, 'shaker', 'c984aed014aec7623a54f0591da07a85fd4b762d', 'shaker@shaker.com', 'shaker shaker', 0, 0, 1, '2018-01-07', 'uploads/avatars/767077_23132131_326738577799649_6010667610545994801_n.jpg'),
(29, 'sameh', 'c984aed014aec7623a54f0591da07a85fd4b762d', 'sam@s.com', 'sameh asad', 0, 0, 1, '2018-01-08', 'uploads/avatars/8945659_22687596_1464504410299939_8738609648740750783_n.jpg'),
(30, 'magdy', 'c984aed014aec7623a54f0591da07a85fd4b762d', 'mag@m.com', 'magdy magdy', 0, 0, 1, '2018-01-08', 'uploads/avatars/991163_house.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comID`),
  ADD KEY `const3` (`item_ID`),
  ADD KEY `const4` (`user_ID`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_ID`),
  ADD KEY `const1` (`member_ID`),
  ADD KEY `const2` (`cat_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `const3` FOREIGN KEY (`item_ID`) REFERENCES `items` (`item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `const4` FOREIGN KEY (`user_ID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `const1` FOREIGN KEY (`member_ID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `const2` FOREIGN KEY (`cat_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
