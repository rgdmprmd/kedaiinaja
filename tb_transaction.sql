-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2021 at 02:30 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kedaiinaja`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaction`
--

CREATE TABLE `tb_transaction` (
  `id` int(11) NOT NULL,
  `status_code` varchar(3) DEFAULT NULL,
  `status_message` varchar(80) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `order_id` varchar(10) DEFAULT NULL,
  `gross_amount` decimal(20,2) DEFAULT NULL,
  `payment_type` varchar(40) DEFAULT NULL,
  `transaction_time` datetime DEFAULT NULL,
  `transaction_status` varchar(40) DEFAULT NULL,
  `bank` varchar(40) DEFAULT NULL,
  `va_number` varchar(40) DEFAULT NULL,
  `fraud_status` varchar(40) DEFAULT NULL,
  `bca_va_number` varchar(40) DEFAULT NULL,
  `permata_va_number` varchar(40) DEFAULT NULL,
  `pdf_url` varchar(200) DEFAULT NULL,
  `finish_redirect_url` varchar(200) DEFAULT NULL,
  `bill_key` varchar(20) DEFAULT NULL,
  `biller_code` varchar(5) DEFAULT NULL,
  `dt_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_transaction`
--

INSERT INTO `tb_transaction` (`id`, `status_code`, `status_message`, `transaction_id`, `order_id`, `gross_amount`, `payment_type`, `transaction_time`, `transaction_status`, `bank`, `va_number`, `fraud_status`, `bca_va_number`, `permata_va_number`, `pdf_url`, `finish_redirect_url`, `bill_key`, `biller_code`, `dt_update`) VALUES
(1, '201', 'Transaksi sedang diproses', '3df9865b-d9a4-48d8-b1ef-fab1804b8202', '2022726780', '94000.00', 'bank_transfer', '2021-06-03 10:35:31', 'pending', NULL, NULL, 'accept', '89622838471', NULL, 'https://app.sandbox.midtrans.com/snap/v1/transactions/4289acf1-762a-4711-bda8-7d68f4f3800a/pdf', 'http://example.com?order_id=2022726780&status_code=201&transaction_status=pending', NULL, NULL, '2021-06-03 10:35:42'),
(2, '201', 'Transaksi sedang diproses', 'd643b82d-4884-4c9b-84f3-18e2fdd2919f', '386187641', '94000.00', 'bank_transfer', '2021-06-03 10:44:55', 'pending', 'bca', '89622584553', 'accept', '89622584553', NULL, 'https://app.sandbox.midtrans.com/snap/v1/transactions/4771ba67-d737-4562-881d-a3bbe17ec2e9/pdf', 'http://example.com?order_id=386187641&status_code=201&transaction_status=pending', NULL, NULL, '2021-06-03 10:44:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_transaction`
--
ALTER TABLE `tb_transaction`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_transaction`
--
ALTER TABLE `tb_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
