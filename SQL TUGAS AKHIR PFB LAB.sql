SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


CREATE DATABASE IF NOT EXISTS `furniland` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `furniland`;

CREATE TABLE `cart` (
  `cartID` int(10) NOT NULL,
  `userID` int(10) DEFAULT NULL,
  `productID` int(10) DEFAULT NULL,
  `quantity` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `cart` (`cartID`, `userID`, `productID`, `quantity`) VALUES
(1, 7, 1, 1),
(2, 7, 3, 2),
(3, 8, 2, 1),
(4, 8, 4, 1),
(5, 9, 5, 1),
(6, 10, 1, 1),
(7, 10, 2, 1),
(8, 11, 3, 3),
(9, 11, 4, 1),
(10, 11, 5, 2),
(11, 7, 1, 12);

CREATE TABLE `products` (
  `productID` int(10) NOT NULL,
  `productName` varchar(30) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `vendorID` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `products` (`productID`, `productName`, `description`, `price`, `image`, `vendorID`) VALUES
(1, 'Felix Accent Armchair', 'Mid-century armchair with soft velvet fabric and gold metal legs.', 1950000, 'Chair_Accent_Armchair.jpg', 1),
(2, 'Kyra Dining Set (4 Chairs)', 'Stylish wood table with cushioned chairs, perfect for modern homes.', 3750000, 'Kyra_Dinning_Set.jpg', 3),
(3, 'Zeno Floating Wall Shelf', 'Wall-mounted shelf made of engineered wood and easy to install.', 499000, 'Zeno_Floating_Shelf.jpg', 3),
(4, 'Chae\'s Study Table + Drawer', 'Study desk with two side drawer unit and smooth oak finish.', 1675000, 'Chae_Study_Table.jpg', 1),
(5, 'Verra Minimalist Coffee Table', 'Round table with tempered glass top and matte black steel frame.', 1150000, 'Verra_Minimalist_Coffee_Table.jpeg', 2),
(9, 'Kimmy Foxi Sofabed', 'Luxurious dark brown tufted velvet sofabed with 100% full silicone filling for maximum comfort. ', 398000, 'Kimmy_Sofa.jpg', 1),
(11, 'Mid-Century Lounge Chair', 'Elegant grey fabric accent chair with solid teak wood frame and angled armrests.', 189000, 'Double_Mother_Chair\'s.jpg', 3),
(12, 'Mid-Century Modern Chair', 'A stylish two-drawer nightstand featuring classic Mid-Century Modern design.', 59000, 'Mid-Century Modern Table.jpg', 3);

CREATE TABLE `transactions` (
  `transactionID` int(10) NOT NULL,
  `userID` int(10) DEFAULT NULL,
  `totalPrice` float DEFAULT NULL,
  `transactionDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `transactions` (`transactionID`, `userID`, `totalPrice`, `transactionDate`) VALUES
(1, 7, 2948000, '2025-11-25'),
(2, 8, 5425000, '2025-11-26'),
(3, 9, 1150000, '2025-11-27'),
(4, 10, 5700000, '2025-11-28'),
(5, 11, 6488000, '2025-11-29'),
(6, 7, 19500000, '2025-12-07'),
(7, 7, 115000000, '2025-12-07'),
(8, 7, 1675000, '2025-12-09'),
(9, 7, 1950000, '2025-12-09'),
(10, NULL, 2647000, '2025-12-10');

CREATE TABLE `transaction_details` (
  `detailID` int(10) NOT NULL,
  `transactionID` int(10) DEFAULT NULL,
  `productID` int(10) DEFAULT NULL,
  `quantity` int(5) DEFAULT NULL,
  `subtotal` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `transaction_details` (`detailID`, `transactionID`, `productID`, `quantity`, `subtotal`) VALUES
(1, 1, 1, 1, 1950000),
(2, 1, 3, 2, 998000),
(3, 2, 2, 1, 3750000),
(4, 2, 4, 1, 1675000),
(5, 3, 5, 1, 1150000),
(6, 4, 1, 1, 1950000),
(7, 4, 2, 1, 3750000),
(8, 5, 3, 3, 1497000),
(9, 5, 4, 1, 1675000),
(10, 5, 5, 2, 2300000),
(11, 6, 1, 10, 19500000),
(12, 7, 5, 100, 115000000),
(13, 8, 4, 1, 1675000),
(14, 9, 1, 1, 1950000),
(15, 10, 3, 3, 1497000),
(16, 10, 5, 1, 1150000);

CREATE TABLE `users` (
  `userID` int(10) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` varchar(6) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `role` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`userID`, `username`, `email`, `password`, `gender`, `dob`, `role`) VALUES
(4, 'admin_john', 'admin1@gmail.com', 'admin123', 'Male', '1990-05-15', 'admin'),
(5, 'admin_sarah', 'admin2@gmail.com', 'securepass', 'Female', '1988-12-03', 'admin'),
(6, 'admin_mike', 'admin3@gmail.com', 'password1', 'Male', '1992-08-22', 'admin'),
(7, 'Jhon De Floretto', 'Flojhon251@gmail.com', 'jhonokok123', 'Male', '2007-01-03', 'member'),
(8, 'Garnet', 'Garnet_Ganteng@gmail', 'garnet123', 'Male', '2008-06-28', 'member'),
(9, 'louis', 'louis@gmail.com', 'louisip', 'Male', '2025-11-29', 'member'),
(10, 'Rico', 'RIco@gmail.com', 'rico123456', 'Male', '2025-11-04', 'member'),
(11, 'Jepek', 'Jepek@gmail.com', 'jepek12345678910', 'Male', '2006-01-03', 'member'),
(12, 'Kevin', 'Kevin@gmail.com', 'Jhon123', 'Male', '2025-12-01', 'member'),
(13, 'nicolamarvels', 'nicolams@gmail.com', 'aaaaaaa', 'Male', '2006-09-30', 'member'),
(14, 'user', 'user@gmail.com', 'user12345678', 'Male', '2007-12-31', 'member'),
(22, 'janus', 'janus@gmail.com', 'janus123456', 'Male', '2019-12-31', 'member'),
(23, 'kolom123yo', 'kolom123yo@gmail.com', 'kolomgogoy2s', 'Male', '2007-02-01', 'member'),
(24, 'garryku123', 'garry@gmail.com', 'garryku123', 'Female', '2008-01-02', 'member');

CREATE TABLE `vendors` (
  `vendorID` int(10) NOT NULL,
  `vendorName` varchar(20) NOT NULL,
  `location` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `vendors` (`vendorID`, `vendorName`, `location`) VALUES
(1, 'FurniCraft Indonesia', 'Jepara, Jawa Tengah'),
(2, 'UrbanLiving Furnishi', 'Jakarta Barat'),
(3, 'PrimeWood Jepara', 'Jepara, Jawa Tengah'),
(4, 'SolidTree Furniture', 'Bandung, Jawa Barat'),
(5, 'Artika Sofa & Living', 'Surabaya, Jawa Timur'),
(6, 'Mitra Office Supply', 'Tangerang Selatan, Banten'),
(7, 'HomeStyle Furnishing', 'Bekasi, Jawa Barat'),
(8, 'RoyalWood Interiors', 'Semarang, Jawa Tengah'),
(9, 'Citra Mebel Nusantar', 'Yogyakarta'),
(10, 'ModernSpace Office F', 'Depok, Jawa Barat'),
(11, 'ace', 'jakarta timur'),
(12, 'Luivton', 'Jakarta Utara');

ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `productID` (`productID`);

ALTER TABLE `products`
  ADD PRIMARY KEY (`productID`),
  ADD KEY `vendorID` (`vendorID`);

ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transactionID`),
  ADD KEY `userID` (`userID`);

ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`detailID`),
  ADD KEY `transactionID` (`transactionID`),
  ADD KEY `productID` (`productID`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `vendors`
  ADD PRIMARY KEY (`vendorID`);

ALTER TABLE `cart`
  MODIFY `cartID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

ALTER TABLE `products`
  MODIFY `productID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

ALTER TABLE `transactions`
  MODIFY `transactionID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

ALTER TABLE `transaction_details`
  MODIFY `detailID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

ALTER TABLE `users`
  MODIFY `userID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

ALTER TABLE `vendors`
  MODIFY `vendorID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`vendorID`) REFERENCES `vendors` (`vendorID`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `transaction_details`
  ADD CONSTRAINT `transaction_details_ibfk_1` FOREIGN KEY (`transactionID`) REFERENCES `transactions` (`transactionID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaction_details_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;