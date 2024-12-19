-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 19, 2024 at 02:48 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('active','inactive','','') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
  `type` enum('admin','staff','','') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'admin',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `password_hash`, `status`, `type`) VALUES
(2, 'tho', '$2y$10$89nxYofDtQgGNVS7YqzbMO3B8bqLA7Yfw1lP1Ltn1XDzaWq60rkf6', 'active', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
CREATE TABLE IF NOT EXISTS `brands` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `category_id`, `name`) VALUES
(1, 1, 'Burberry'),
(2, 1, 'Calvin Klein'),
(3, 1, 'Chanel'),
(4, 1, 'Gucci'),
(5, 1, 'Versace'),
(6, 1, 'Laurrent'),
(7, 2, 'Burberry'),
(8, 2, 'Calvin Klein'),
(9, 2, 'Chanel'),
(10, 2, 'Gucci'),
(11, 2, 'Versace'),
(12, 2, 'Laurrent');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
CREATE TABLE IF NOT EXISTS `carts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(13, 31, 4, 3),
(14, 31, 3, 1),
(74, 33, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Nước hoa nam'),
(2, 'Nước hoa nữ');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `status` enum('processing','confirmed','shipping','dellvered','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'processing',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `consignee_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `consignee_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `consignee_phone_number` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `delivery_date` date NOT NULL,
  `payment_method` enum('cod','onl','atm') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `total_quantity` int NOT NULL,
  `total_price` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `status`, `created_at`, `updated_at`, `consignee_name`, `consignee_address`, `consignee_phone_number`, `delivery_date`, `payment_method`, `total_quantity`, `total_price`) VALUES
(63, 33, 'processing', '2024-12-18 10:30:53', '2024-12-18 10:30:53', 'Thọ', '133/28A An Duong Vuong, P16, Q8, HCM', '0934155548', '2024-12-20', 'cod', 2, 9000000),
(64, 33, 'processing', '2024-12-18 11:05:53', '2024-12-18 11:05:53', 'Thọ', '133/28A An Duong Vuong, P01, Q8, HN', '0934155548', '2024-12-20', 'cod', 2, 4960000),
(65, 33, 'processing', '2024-12-18 22:56:22', '2024-12-18 22:56:22', 'Thọ', '133/28A An Duong Vuong, P01, Q8, HCM', '0934155548', '2024-12-21', 'cod', 3, 22671000);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(73, 63, 1, 2, 4500000),
(74, 64, 4, 2, 2480000),
(75, 65, 2, 3, 7557000);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `brand_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` double NOT NULL,
  `discounted_price` double NOT NULL,
  `quantity` int NOT NULL,
  `size` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `origin` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `year_of_release` year NOT NULL,
  `incense_group` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `style` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `brand_id` (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `brand_id`, `name`, `description`, `price`, `discounted_price`, `quantity`, `size`, `origin`, `year_of_release`, `incense_group`, `style`) VALUES
(1, 1, 'Burberry Clary Sage', 'Hương đầu: Bạch đậu khấu, Xô thơm\r\nHương giữa: Nhựa cây, Da thuộc\r\nHương cuối: Xô thơm, Cỏ khô, Cỏ hương bài\r\n\r\nNằm trong bộ sưu tập “Bespoke\" của nhà Burberry, Clary Sage là một tông màu trầm của nước hoa với vẻ đẹp sáng đặc, sâu thẳm đậm vị gỗ của mình. Ở đây, hương gỗ không đơn thuần dừng lại ở việc trầm và ấm, mà nó ngúng nguẩy, toát lên ánh nhìn kén chọn và kiêu kỳ hơn nhiều.\r\n\r\nNgay từ tầng hương đầu tiên, Xô thơm đã xuất hiện, chiếm lấy khứu giác với tông vị thô ráp, hoang dại đặc trưng của mình. Để rồi Nhựa cây cùng Da thuộc dần dà được thêm vào như gọt xén bớt đi nét \'khó chiều\' ấy, tung hứng chút ngọt hăng và khiến tổng thể trở nên có chiều sâu hơn hẳn. \r\n\r\nCho đến cuối, Burberry Clary Sage vẫn không làm mất đi Xô thơm - nét đẹp phóng túng, tự do làm nên màu sắc chính cho mùi hương - kèm theo chút điềm nhiên Cỏ khô và Cỏ hương bài như một cách khẳng định sự \'ngỗ ngược\' ngầm mà mình đang mang.', 6500000, 4500000, 30, '60', 'Anh, Pháp, Tây Ban Nha', '2017', 'Bạch đậu khấu, Cây xô thơm, Thảo mộc', 'Tinh tế, Thanh lịch, Sang trọng'),
(2, 1, 'Burberry Her Eau de Toilette', 'Hương đầu: Quả lê, Dâu tây, Quả lý chua đen, Hạt tiêu hồng\r\nHương giữa: Hoa mẫu đơn, Hoa kim ngân, Hoa huệ tây, Hoa hồng\r\nHương cuối: Xạ hương, Tuyết tùng\r\n\r\nBurberry Her Eau de Toilette là một trong những đoá hoa trong khu vườn nhỏ mang tên \"Burberry Her\". Một đoá hoa với sắc xanh dịu mát, màu sắc của sự tươi trẻ, của một tinh thần tự do và đầy sành điệu của cô nàng thời đại mới.\r\n\r\nMỗi khi hè sang, đoá hoa ấy lại như chiếm hết sóng, cứ tự tin mà toả sáng, đem cái hương thơm trong trẻo, tươi mới của mình lấn át hết những u uất, muộn phiền. Với sự kết hợp tưởng chừng không hợp nhưng lại vô cùng hoàn hảo từ cái ngon ngọt của Quả lê, Dâu tây, chút chua nhẹ của Lý chua đen cùng chút cay nồng của Hạt tiêu đã gây cho tôi một ấn tượng mạnh mẽ. Đến tầng hương giữa lại đưa tâm hồn tôi lơ đễnh trôi dạt vào khu vườn nhỏ ngập sắc hương ấy với Hoa mẫu đơn thơm ngát, Hoa kim ngân nồng nàn và một nốt Hồng tinh tế. Hương thơm kết màn với tầng hương cuối đẹp đến xao xuyến, bởi cái ấm áp của Tuyết tùng, cái ngọt ngào của Xạ hương đã thành công vỗ ngọt khứu giác tôi.\r\n\r\nBurberry Her Eau de Toilette như dành riêng cho mùa hè, và chủ sở hữu là những cô gái trẻ luôn đầy sự tự tin, tươi trẻ và một tinh thần tràn đầy năng lượng. Một khi đã tiếp xúc rồi sẽ khiến người ta nhớ mãi không thôi, cái thứ hương với đủ mọi sắc thái xinh đẹp nhất.', 8500000, 7557000, 10, '60', 'Anh, Pháp', '2022', 'Quả lê, Xạ hương, Hoa kim ngân, Hoa mẫu đơn', 'Quả lê, Xạ hương, Hoa kim ngân, Hoa mẫu đơn'),
(3, 2, 'Calvin Klein CK Everyone Eau de Parfum', 'Hương đầu: Cam\r\nHương giữa: Trà đen,\r\nHương cuối: Cỏ hương bài\r\n\r\nĐúng với tinh thần của thương hiệu Calvin Klein hướng tới, dòng nước hoa tiếp nối, Phiên bản Eau De Parfum của CK Everyone cũng là một chai nước hoa dành cho tất cả mọi người, theo đúng tên gọi của nó.\r\n\r\nCK Everyone EDP mở ra với vị thanh chua nhẹ nhàng và vô cùng tinh tế của Cam. Từng tép cam bùng nổ, kích hoạt khứu giác và vị giác của con người. Mùi hương tinh tế của Cam còn được đồng hành cùng vị Trà Đen, một tổ hợp chắc chắn sẽ mang lại cảm giác như bạn đang ngồi trong những căn phòng cạnh một khu vườn vào những buổi sớm tinh mơ và thưởng thức một ly trà thơm ngát. Không đơn thuần chỉ là một mùi Cam chanh phổ thông, CK Everyone EDP có thêm chiều sâu nhờ vào tông vị của Cỏ hương bài.\r\n\r\nCK Everyone Eau de Parfum vẫn giữ đúng tiêu chí của phiên bản ra mắt trước, là một mùi hương dành cho mọi người, nam hay nữ, độ tuổi nào, bất kể ngoại hình như thế nào cũng có thể tự tin diện mùi hương này. Đặc biệt, với nồng độ EDP thì độ bám tỏa của chai nước hoa cũng được nâng tầm lên đáng kể.', 1500000, 950000, 0, '60', 'Mỹ, Pháp', '2022', 'Lá trà đen, Cỏ hương bài, Hương cam', 'Tưới mới, Nhẹ nhàng, Thanh lịch'),
(4, 3, 'Chanel Bleu De Chanel Parfum', 'Hương đầu: Quả bưởi, Quả chanh vàng, Bạc hà, Tiêu hồng, Cam Bergamot, Aldehydes, Hạt rau mùi.\r\nHương giữa: Gừng, Nhục đậu khấu, Hoa nhài, Quả dưa.\r\nHương cuối: Nhang, Nhựa hổ phách, Gỗ tuyết tùng, Gỗ đàn hương, Hoắc hương, Nhựa Labdanum, Nhựa Hổ phách.\r\n\r\nSự ra mắt của Bleu de Chanel năm 2010 giống như một người khởi xướng cho phong trào làm nước hoa “blue” tới từ các nhà hương, một phong cách mùi hương nịnh mũi, dễ gần và vô cùng đa dụng. Bleu de Chanel dường như đáp ứng đầy đủ các yêu cầu dành cho cánh mày râu khi cần tìm kiếm một mùi hương gây được dấu ấn của bản thân mình thời điểm đó. Với sự chu toàn của Chanel, việc cho ra một phiên bản nâng cấp để hoàn thiện tính hoàn hảo cho mùi hương là điều chắc chắn xảy ra, và chúng ta đã có Bleu de Chanel Eau de Parfum vào năm 2014.\r\n\r\nVốn được rất nhiều người trong cộng đồng nước hoa đánh giá là phiên bản hoàn hảo nhất của dòng Bleu de Chanel, Bleu de Chanel Eau de Parfum vẫn đem tới cho các quý ông một khởi đầu tươi sáng và thanh lịch. Bưởi và Gừng, thứ được cho là dấu ấn của dòng hương nổi tiếng của Chanel, được tô đậm và làm sáng lên rõ ràng trong Bleu de Chanel Eau de Parfum, đa sắc và dày dặn hơn. Đi vào tâm của mùi hương cũng vậy, khi phiên bản Bleu de Chanel Eau de Parfum sở hữu một mùi hương có chiều sâu rõ rệt với sự xuất hiện của Nhựa hổ phách, bên cạnh hương Gỗ và Nhang đã tạo nên nhận diện đặc trưng ở tầng hương cuối cho dòng hương Bleu de Chanel.\r\n\r\nVốn được sinh ra để thỏa mãn tất cả những người yêu thích phong cách hương thơm “kiểu Bleu de Chanel”, phiên bản Eau de Parfum này thực sự là một mảnh ghép hoàn hảo được Chanel đem tới để làm mãn nguyện những quý ông yêu thích dòng hương này.', 3250000, 2480000, 40, '60', 'Pháp', '2014', 'Bưởi, Hương nhang, Hổ phách, Chanh vàng', 'Gợi cảm, Sang Trọng, Tinh tế, Lịch lãm'),
(5, 10, 'Gucci Bloom Eau de Parfum Intense', 'Hương đầu: Quả lê, Gừng, Cam quýt\r\nHương giữa: Hoa nhài, Hoa nhuệ, Hoa cam\r\nHương cuối: Hoắc hương, Rêu sồi, Dừa\r\n \r\nGucci luôn biết cách gây ấn tượng với những người yêu hương bằng cách biến tấu các flanker của mình. Gucci Bloom EDP sang trọng và tinh tế với hoa trắng, Gucci Bloom EDT tươi mới, trẻ trung với chút cam chanh, Gucci Bloom Profumo di Fiori mùi mẫn và ngọt ngào qua Ngọc lan tây. Cứ như thế, các nàng thơ mang tên Gucci Bloom đều tỏa sắc, tỏa hương theo một phong cách rất riêng biệt.\r\n \r\nGucci Bloom EDP Intense được nhận xét là hương thơm cá tính và táo bạo nhất của dòng Gucci Bloom, khi mang cho mình tông hương tối màu, hơi ẩm và xanh qua Hoắc hương và Rêu sồi. Một chút ngọt thanh của Lê được thêm vào, nhưng tất nhiên vẫn không đủ để \"tô sáng\" cho tổng thể.\r\n \r\nSự dày dặn tiếp nối sự dày dặn, Hoa nhài trong Gucci Bloom EDP Intense không tươi sáng kiều diễm, mà lần này lại đậm đà và bí ẩn. Để so sánh cho dễ hiểu nhất, thì Gucci Bloom EDP là nụ Nhài xuân, mơn mởn và tinh tươm, trong khi Gucci Bloom EDP Intense lại là những bông hoa Nhài nở về đêm, nồng nàn và cuốn hút hơn rất nhiều.', 3800000, 2500000, 100, '60', 'Anh, Đức, Pháp', '2023', 'Hương hoa huệ, Hoa nhài, Quảng hoắc hương', 'Bí ẩn, Ngọt ngào, Sang trọng'),
(6, 8, 'Calvin Klein CK Be', 'Hương đầu: Hoa oải hương, Nốt hương xanh, Cam Bergamot, Bạc hà, Lá bách xù, Quả quýt.\r\nHương giữa: Hương cỏ xanh, Quả đào, Hoa nhài, Hoa lan Nam Phi, Hoa mộc lan, Hoa phong lan.\r\nHương cuối: Xạ hương, Gỗ đàn hương, Gỗ tuyết tùng, Vanilla, Nhựa hổ phách, Nhựa Opoponax.\r\n\r\nCK Be là một phiên bản tiếp nối sau sự thành công vang dội của CK One. Vẫn với phong cách mùi hương unisex, CK Be là một mùi hương tươi mát, sảng khoái tương tự người tiền nhiệm, nhưng được thêm vào một chút tính cánh riêng để tạo nên những điểm nhấn của riêng mình.\r\n\r\nCam chanh xuất hiện như một phần linh hồn của dòng hương này, tươi sáng, nhẹ nhàng, phảng phất qua lại giữa các lớp hương. CK Be nhấn mạnh vào những nốt hương xanh, ngai ngái, tươi màu lá, hòa quyện vào đó một chút thư thái cổ điển của Oải hương, đem tới cảm giác an lành dễ chịu. Những lớp hoa đan xen hòa vào cái khối hương ấy, thổi vào CK Be chất phi giới tính cân bằng, mọi thứ được giữ mở mức vừa đủ, không quá trớn, cũng không nhạt nhòa.\r\n\r\nGiống như CK One, CK Be cũng đã trở thành một mùi hương được yêu thích trên toàn cầu, bảo chứng cho chất lượng không thể phủ nhận của hương thơm này.', 1080000, 900000, 5, '60', 'Monaco, Ý', '0000', 'Lá trà đen, Cỏ hương bài, Hương cam', 'Tưới mới, Nhẹ nhàng, Thanh lịch');

-- --------------------------------------------------------

--
-- Table structure for table `products_imgs`
--

DROP TABLE IF EXISTS `products_imgs`;
CREATE TABLE IF NOT EXISTS `products_imgs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `images` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products_imgs`
--

INSERT INTO `products_imgs` (`id`, `product_id`, `images`) VALUES
(1, 1, 'Burberry_Clary_Sage_1.webp'),
(2, 1, 'Burberry_Clary_Sage_2.webp'),
(4, 2, 'Burberry_Her_Eau_de_Toilette_1.webp'),
(6, 3, 'Calvin_Klein_CK_Everyone_Eau_de_Parfum_1.webp'),
(7, 4, 'Chanel_Bleu_De_Chanel_Parfum_1.webp'),
(8, 5, 'Gucci_Bloom_Eau_de_Parfum_Intense_1.webp'),
(9, 6, 'Calvin_Klein_CK_Be_1.webp');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `verify` tinyint(1) NOT NULL DEFAULT '0',
  `otp` varchar(8) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `first_name`, `last_name`, `password_hash`, `phone`, `verify`, `otp`) VALUES
(31, 'auduongtuyen2410@gmail.com', 'Hoang', 'Tho', '24aea3e6b4ab4e539d8bfe3d84233b649b48f02610fceaf2a8a41dbe66761cda', '0934155548', 1, ''),
(33, 'pemocute24@gmail.com', 'Hoàng', 'Thọ', '24aea3e6b4ab4e539d8bfe3d84233b649b48f02610fceaf2a8a41dbe66761cda', '0934155548', 1, '');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `brands`
--
ALTER TABLE `brands`
  ADD CONSTRAINT `brands_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `products_imgs`
--
ALTER TABLE `products_imgs`
  ADD CONSTRAINT `products_imgs_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
