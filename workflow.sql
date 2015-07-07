SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

--
-- Table structure for table `workflow`
--

CREATE TABLE IF NOT EXISTS `workflow` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Has not been processed yet; 1: Approved; 2: Partially approved / Partially rejected; 3: Fully rejected',
  `expired_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Workflow Table';

-- --------------------------------------------------------

--
-- Table structure for table `workflow_groups`
--

CREATE TABLE IF NOT EXISTS `workflow_groups` (
  `id` int(11) NOT NULL,
  `workflow_step_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Has not been processed yet; 1: Approved; 2: Partially approved / Partially rejected; 3: Fully rejected'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Workflow Groups Definition';

-- --------------------------------------------------------

--
-- Table structure for table `workflow_step`
--

CREATE TABLE IF NOT EXISTS `workflow_step` (
  `id` int(11) NOT NULL,
  `workflow_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Has not been processed yet; 1: Approved; 2: Partially approved / Partially rejected; 3: Fully rejected',
  `expired_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Workflow Step Definition';

-- --------------------------------------------------------

--
-- Table structure for table `workflow_verificator`
--

CREATE TABLE IF NOT EXISTS `workflow_verificator` (
  `id` int(11) NOT NULL,
  `workflow_groups_id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: Has not been processed yet; 1: Approved; 2: Partially approved / Partially rejected; 3: Fully rejected',
  `message` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Workflow User / Verificator Definition';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `workflow`
--
ALTER TABLE `workflow` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workflow_groups`
--
ALTER TABLE `workflow_groups` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workflow_step`
--
ALTER TABLE `workflow_step` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workflow_verificator`
--
ALTER TABLE `workflow_verificator` ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `workflow`
--
ALTER TABLE `workflow` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `workflow_groups`
--
ALTER TABLE `workflow_groups` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `workflow_step`
--
ALTER TABLE `workflow_step` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `workflow_verificator`
--
ALTER TABLE `workflow_verificator` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
