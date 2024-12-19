-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 19, 2024 lúc 04:23 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `shop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `access_logs`
--

CREATE TABLE `access_logs` (
  `id` int(11) NOT NULL,
  `access_date` datetime NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `user_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `access_logs`
--

INSERT INTO `access_logs` (`id`, `access_date`, `ip_address`, `user_agent`, `user_name`) VALUES
(34, '2024-12-18 07:38:28', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', ' Thắng'),
(35, '2024-12-18 07:58:50', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', ' Thắng'),
(36, '2024-12-18 08:05:50', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', ' Admin'),
(37, '2024-12-18 09:02:17', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', ' Admin'),
(38, '2024-12-18 10:09:46', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', ' Thắng'),
(39, '2024-12-18 10:57:40', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', ' Thắng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `content` text DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `published_date` datetime DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `articles`
--

INSERT INTO `articles` (`id`, `title`, `description`, `content`, `author`, `published_date`, `image`) VALUES
(1, 'Kỹ thuật trồng rau sạch trong chậu xốp tại nhà đơn giản', 'Tự trồng rau trong thùng xốp tại nhà là sự lựa chọn của rất nhiều gia đình trong thành phố bởi phương pháp trồng rau đơn giản, dễ trồng, dễ quản lý, an toàn và tiện lợi. Nhưng người trồng cũng cần phải đảm bảo đúng kỹ thuật trồng rau để đảm bảo vệ sinh an toàn thực phẩm và giá trị dinh dưỡng của rau.', 'Tự trồng rau trong thùng xốp tại nhà là sự lựa chọn của rất nhiều gia đình trong thành phố bởi phương pháp trồng rau đơn giản, dễ trồng, dễ quản lý, an toàn và tiện lợi. Nhưng người trồng cũng cần phải đảm bảo đúng kỹ thuật trồng rau để đảm bảo vệ sinh an toàn thực phẩm và giá trị dinh dưỡng của rau.\r\n\r\nKỹ thuật trồng cây rau sạch trong hộp xốp rất dễ thực hiện, chỉ cần bỏ chút công sức và thời gian chờ đợi, những đợt rau sạch tự tay trồng đảm bảo an toàn sẽ đến ngày thu hoạch.\r\n\r\nCách trồng rau sạch tại nhà thì trồng bằng chậu xốp là đơn giản và dễ thực hiện nhất. Ngoài ra, có thể tận dụng thau, rổ, chậu cũ…. để làm vật dụng trồng rau, lưu ý tạo lỗ thoát nước cho những vật dụng này.\r\n\r\n\r\n\r\nSử dụng các vật dụng đơn giản có trong gia đình để tạo nên các chậu trồng cây: Thùng xốp, hạt giống, đất, phân hữu cơ, gạch.\r\n\r\nVới chậu xốp, cần khoét lỗ thoát nước, thường thì từ 6 đến 8 lỗ 1 chậu, không nên khoét to quá, sẽ làm trôi đất, nếu trồng các loại cây cần thoát nước nhanh, có thể dùng lưới thép hoặc lưới nhựa bịt các lỗ vừa khoét trong hộp, vừa đảm bảo thoát nước vừa không bị trôi đất.\r\n\r\nVới các loại thau, chậu, rổ cũ, nên chọn loại nhựa để bền và dễ vệ sinh. Cũng cần đục lỗ giống như hộp xốp để thoát nước,với các loại rổ đã có lỗ, có thể lồng 2 chiếc vào nhau làm 1 chậu để trồng, sẽ bền và tránh mất đất. Tất cả các loại chậu để trồng cần tránh tiếp xúc trực tiếp với mặt đất bằng cách kê cao 4 góc để cân bằng giúp dễ cây lưu thông thoáng.\r\n\r\nLưu ý: Gạch kê tránh lỗ hổng dưới đáy hộp\r\n\r\nKhông gieo quá nhiều hạt giống vào một thùng, tránh tình trạng cây mọc lên sẽ dày năng suất sẽ không cao. Có thể trồng các loại rau thơm như bạc hà, húng,… cùng một thùng, tuy nhiên những loại cây như ớt, cà chua, dưa chuột,… nên trồng riêng ở các thùng khác nhau. Sau khi gieo hạt, dùng vải mỏng phủ lên đễ giữ ấm, kích thích hạt nảy mầm nhanh.\r\n\r\n', 'Thắng', '2024-12-17 05:00:18', 'eat-clean-bi-kip-de-co-than-hinh-xinh-nhu-mo-cua-co-nang-9x-1.jpg'),
(2, 'Eat Clean – bí kíp để có thân hình xinh như mơ của cô nàng 9x', 'Đối với nhiều người, “Eat Clean” vẫn còn mơ hồ như một đích đến xa xăm thì đối với cô gái 9x này hai từ ấy đã trở thành một phần của cuộc sống hàng ngày\r\n', 'Đối với nhiều người, “Eat Clean” vẫn còn mơ hồ như một đích đến xa xăm thì đối với cô gái 9x này hai từ ấy đã trở thành một phần của cuộc sống hàng ngày\r\nTrong bài viết được đăng trên Group Facebook “Chia sẻ nấu ăn và Trồng rau”, Lê Bích Thùy cho biết cô có sở thích ăn chay, chụp ảnh và tập thể thao. Có lẽ vì thế mà mỗi mỗi món ăn cô trình bày, mỗi hình ảnh cô chia sẻ đều có tính thẩm mỹ cực kỳ cao, khiến cho ai nhìn cũng thấy thật bắt mắt và hấp dẫn.\r\n\r\n\r\n\r\nChia sẻ của Bích Thùy đạt gần 600 lượt tương tác và gần 100 lượt bình luận trong group này.\r\n\r\n\r\n\r\nĐược sự cho phép của tác giả, aFamily xin chia sẻ lại một số món ăn theo phương châm Eat Clean đã được Bích Thùy thực hiện nhằm giúp các chị em có nhiều ý tưởng hơn để thực hiện Eat Clean cho bản thân mình, qua đó trở nên khỏe mạnh hơn và ngày càng xinh đẹp hơn nữa nhé!\r\n\r\n\r\n\r\nCombo 1 bữa đầy đủ dinh dưỡng: bông cải xanh hấp, cà rốt sống, cà chua sống, khoai lang nướng muối ớt ăn kèm cải xoăn xào trứng\r\n\r\n\r\n\r\nLê Bích Thùy sinh năm 1991, hiện đang sống và làm việc tại Tp. Hồ Chí Minh. Với những thực đơn Eat Clean và ăn chay vô cùng đẹp mắt, Thùy cho biết có nhiều bạn inbox hỏi cô với chế độ ăn như vậy thì nguồn Protein được cung cấp từ đâu? Cô cho biết với chế độ ăn này nguồn protein không hề thiếu hụt mà ngược lại còn rất dồi dào từ các loại đậu (đậu Hà Lan, đậu gà, đậu đen, đỏ, trắng…), các loại hạt (hạt điều, óc ché, macca…) cùng các loại nấm rất phổ biến chợ nào cũng có.\r\n\r\n', 'Thắng', '2024-12-17 05:00:59', 'eat-clean-bi-kip-de-co-than-hinh-xinh-nhu-mo-cua-co-nang-9x-1.jpg'),
(3, 'Lấy lại vòng eo con kiến nhờ công thức đơn giản từ củ đậu và rau cải', 'Bụng mỡ nhiều đến mấy cũng trở nên phẳng lỳ với công thức từ củ đậu và rau cải mỗi ngày, hãy tham khảo cách làm dưới đây nhé!\r\n', 'Bụng mỡ nhiều đến mấy cũng trở nên phẳng lỳ với công thức từ củ đậu và rau cải mỗi ngày, hãy tham khảo cách làm dưới đây nhé!\r\nChẳng cần tập luyện hay kiêng khem khổ cực, bạn có thể lấy lại vóc dáng eo thon, vòng 2 con kiến chỉ với rau và củ đậu.Với công thức rất đơn giản mà lại hiệu quả vô cùng bạn sẽ có vóc dáng cân đối.\r\n\r\nGIẢM CÂN BẰNG SALAD RAU\r\n\r\nNguyên liệu cần chuẩn bị\r\n\r\n– ¼ bắp cải tím\r\n\r\n– 5-6 quả cà chua bi\r\n\r\n– 50g ngô ngọt\r\n\r\n– 50g bánh mỳ\r\n\r\n– 1 quả trứng gà\r\n\r\n– 2 củ khoai tây nhỏ\r\n\r\n– 20g cá ngừ đóng hộp\r\n\r\n– Sốt salad (hoặc hỗn hợp mayonnaise + dầu olive)\r\n\r\n\r\n\r\nRau cải bắp tím có khả năng đánh bay mỡ thừa cực tốt.\r\n\r\nBắp cải tím là một trong những loại rau đem đến hiệu quả giảm cân cực kì tốt vì bắp cải tím ăn vào sẽ tạo cảm giác no lâu, ít calo mà lại giàu vitamin và khoáng chất. Đồng thời lượng chất xơ dồi dào trong bắp cải sẽ giúp ích rất nhiều cho hệ tiêu hóa, bởi thế salad cải bắp tím có khả năng giảm cân cực tốt.\r\n\r\n\r\n\r\nCách làm:\r\n\r\n– Các nguyên liệu sau khi mua về, bạn rửa sạch với nước, ngâm trong nước muối pha loãng để khử trùng.\r\n\r\n– Sau đó bạn thái nhỏ các loại nguyên liệu và cho vào 1 cái tô lớn.\r\n\r\n– Đổ sốt mayonnaise vào và trộn đều, để chừng 15 phút bạn có thể sử dụng.\r\n\r\nCách sử dụng:\r\n\r\n– Ăn salad cải tím mỗi ngày bạn sẽ giảm ngay được mỡ thừa, lấy lại vòng eo thon gọn.\r\n\r\n\r\n\r\nThường xuyên ăn salad cải bắp tím và xem điều kỳ diệu gì xảy ra nhé.\r\n\r\nGIẢM CÂN BẰNG CỦ ĐẬU\r\n\r\nKhông chỉ là một loại củ giải khát thông thường, củ đậu còn có tác dụng làm đẹp da và giảm cân thần kỳ mà chị em chưa biết. Trong thành phần củ đậu có đến 80-90% là nước, 4,51% đường glucoza, 2,4% tinh bột. Ngoài ra củ đậu còn chứa nhiều chất dinh dưỡng, vitamin và muối khoáng khác như: sắt, canxi, photpho, vitamin C… có khả năng đánh bay mỡ thừa và dưỡng da trắng hồng tự nhiên.\r\n\r\n\r\n\r\nTuy nhiên, bạn cũng không nên quá lạm dụng củ đậu để tránh làm cơ thể bị thiếu dinh dưỡng và gây nên mệt mỏi.\r\n\r\nChúc các bạn thành công và luôn xinh đẹp.\r\n\r\n', 'Thắng', '2024-12-17 05:01:30', 'lay-lai-vong-eo-con-kien-nho-cong-thuc-don-gian-tu-cu-dau-va-rau-cai.jpg'),
(5, 'KM: Tháng giải phóng mỡ thừa, da xấu, độc tố trong cơ thể', 'Tháng 4, hoà trong không khí giải phóng dân tộc, đối với chúng tôi, đây là tháng: GIẢI PHÓNG MỠ THỪA, KHÔNG CHỪA 1 LẠNG\r\n\r\n', 'Tháng 4, hoà trong không khí giải phóng dân tộc, đối với chúng tôi, đây là tháng: GIẢI PHÓNG MỠ THỪA, KHÔNG CHỪA 1 LẠNG\r\n\r\nKế hoạch cho những ngày nghỉ lớn của tháng 4 đã sẵn sàng, tuy nhiên bạn vẫn chưa cảm thấy tự tin với vóc dáng, làn da chưa được đẹp, cơ thể khó chịu, thiếu sức sống. Giờ là lúc bạn lấy lại vóng dáng chuẩn, làn da tươi trẻ và cơ thể tươi trẻ từ bên trong bằng liệu trình detox giảm cân, trẻ hoá làn da, đào thải chất cặn bả trong cơ thể từ công nghệ ÉP LẠNH HPP đầu tiên tại Việt Nam.\r\n\r\n\r\n\r\nLiệu trình đã chiếm được sự tin yêu của khách hàng trong hơn 2 năm qua, đã giúp hơn 19,888 khách hàng tại thành phố Hồ Chí Minh tự tin hơn mỗi ngày và thành công hơn trong cuộc sống.\r\n\r\nBí quyết liệu trình detox giảm cân của Fresh Saigon\r\n\r\nGiảm 2-4kg: Sự kết hợp của hơn 10 loại trái cây, rau củ như Ớt chuông có chứa chất capsaicin có tác dụng đốt cháy chất béo hiệu quả và làm cơ thể sử dụng nhiều calo hơn ngay sau bữa ăn.\r\n\r\nChống lão hoá, tươi trẻ từ hơn 40 loại vitamin, vitamin C, khoáng chất, chất xơ hàm lượng cao giúp cho quá trình detox thanh lọc cho bạn 1 làn da tươi trẻ từ bên trong. Những trái cây giàu Vitamin C hay chất chống oxy hoá đều có trong liệu trình giảm cân như thanh long đỏ,…\r\n\r\nKhông đường, nước ép TƯƠI thật 100%, công nghệ ÉP LẠNH HPP lần đầu tiên có mặt tại Việt Nam, Nature Care đã mang trọn dưỡng chất của trái cây tươi vào trong sản phẩm mà không 1 công nghệ ép nào trên thị trường có được. Hàm lượng dinh dưỡng hơn 40% so với cách ép hiện nay, thời gian sử dụng lên đến 30 ngày mà không chứa bất kỳ chất bảo quản nào. Sản phẩm đạt tiêu chuẩn của Clean Label. Tất cả sản phẩm của Nature Care không có thêm đường hay bất kỳ hương phụ liệu, tất cả là từ thiên nhiên.\r\n\r\nThanh lọc cơ thể: Nhiều chất xơ, hỗ trợ đào thải chất cặn bả trong ruột mang đến hệ tiêu hoá khoẻ mạnh từ bên trong. \r\n\r\nSau khi kết thúc liệu trình, bạn còn có thể chọn chế độ tái detox thường xuyên hàng tháng tại Nature Care với nhiều ưu đãi hấp dẫn để có được thói quen ăn uống lành mạnh tốt cho sức khỏe. Đó cũng là lý do tại sao Detox của Nature Care được mệnh danh là thức uống làm đẹp của hoa hậu, người đẹp: Hoa hậu Ngọc Diễm, hoa hậu Ngụy Thanh Lan, diễn viên Thanh Trúc, diễn viên Yaya Trương Nhi; các beauty blogger và hàng ngàn khách hàng luôn tin chọn liệu trình detox từ Nature Care.\r\n\r\n', 'Thắng', '2024-12-17 05:01:56', 'km-thang-giai-phong-mo-thua-da-xau-doc-to-trong-co-the-e1648746180713.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `message`, `created_at`) VALUES
(2, 'NGUYỄN THỊ NGỌC TRÂM', 'badaotulong123@gmail.com', '0397970507', 'd', '2024-12-17 07:38:08'),
(3, 'NGUYỄN THỊ NGỌC TRÂM', 'badaotulong123@gmail.com', '0397970507', 'd', '2024-12-17 07:38:18'),
(4, 'NGUYỄN THỊ NGỌC TRÂM', 'badaotulong123@gmail.com', '0397970507', 'Test', '2024-12-17 07:38:24'),
(5, 'NGUYỄN THỊ NGỌC TRÂM', 'badaotulong123@gmail.com', '0397970507', 'Test', '2024-12-17 07:41:06'),
(6, 'Lê Văn Thắng', 'badaotulong123@gmail.com', '0914476792', 'd', '2024-12-17 07:41:47'),
(7, 'Lê Văn Thắng', 'badaotulong123@gmail.com', '0914476792', 'd', '2024-12-17 07:43:20'),
(8, 'Lê Văn Thắng', 'badaotulong123@gmail.com', '0914476792', 'd', '2024-12-17 07:43:21'),
(9, 'Lê Văn Thắng', 'badaotulong123@gmail.com', '0914476792', 'd', '2024-12-17 07:43:26'),
(10, 'Lê Văn Thắng', 'badaotulong123@gmail.com', '0914476792', 'd', '2024-12-17 07:45:20'),
(11, 'Lê Văn Thắng', 'badaotulong123@gmail.com', '0914476792', 'Test1', '2024-12-17 07:45:38'),
(12, 'Lê Văn Thắng', 'badaotulong123@gmail.com', '0914476792', 'Test1', '2024-12-17 07:45:40'),
(13, 'Lê Văn Thắng', 'badaotulong123@gmail.com', '0914476792', 'Test1', '2024-12-17 07:45:41'),
(14, 'Lê Văn Thắng', 'badaotulong123@gmail.com', '0914476792', 'Test1', '2024-12-17 07:45:41'),
(15, 'Lê Văn Thắng', 'badaotulong123@gmail.com', '0914476792', 'Test1', '2024-12-17 07:45:42'),
(16, 'Lê Văn Thắng', 'badaotulong123@gmail.com', '0914476792', 'Test1', '2024-12-17 07:45:42'),
(17, 'Lê Văn Thắng', 'badaotulong123@gmail.com', '0914476792', 'Test1', '2024-12-17 07:46:00'),
(18, 'Lê Văn Thắng', 'badaotulong123@gmail.com', '0914476792', 'Test1', '2024-12-17 07:46:19'),
(19, 'Lê Văn Thắng', 'badaotulong123@gmail.com', '0914476792', 'Test2', '2024-12-17 07:48:17'),
(20, 'Lê Văn Thắng', 'badaotulong123@gmail.com', '0914476792', 'Test3', '2024-12-17 07:49:15'),
(21, 'Lê Văn Thắng', 'badaotulong123@gmail.com', '0914476792', 'Test3', '2024-12-17 07:49:37'),
(22, 'Lê Văn Thắng', 'badaotulong123@gmail.com', '0914476792', 'Test3', '2024-12-17 07:50:39'),
(23, 'Lê Văn Thắng', 'badaotulong123@gmail.com', '0914476792', 'Test3', '2024-12-17 07:51:46'),
(24, 'Lê Văn Thắng', 'badaotulong123@gmail.com', '0914476792', 'Test4', '2024-12-17 07:59:44');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contact_info`
--

CREATE TABLE `contact_info` (
  `id` int(11) NOT NULL,
  `icon_image` varchar(255) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `contact_detail` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `contact_info`
--

INSERT INTO `contact_info` (`id`, `icon_image`, `contact_name`, `contact_detail`) VALUES
(1, 'hotline.jpg', 'Hotline', 'tel:0914476792'),
(2, 'zalo2-01.png', 'Zalo', 'https://zalo.me/0914476792'),
(3, '6806987.png', 'Email', 'mailto:badaotulong123@gmail.com');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `embed_code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `locations`
--

INSERT INTO `locations` (`id`, `name`, `latitude`, `longitude`, `embed_code`) VALUES
(1, 'Thắng Rai', 0, 0, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3894.8324265095875!2d108.30764557585289!3d12.527257924462795!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317193b603af7f3b%3A0x71c85315931a80b0!2zVGjhuq9uZyBSYWk!5e0!3m2!1svi!2s!4v1734422958114!5m2!1svi!2s\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `logos`
--

CREATE TABLE `logos` (
  `id` int(11) NOT NULL,
  `logo_name` varchar(255) NOT NULL,
  `logo_image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `logos`
--

INSERT INTO `logos` (`id`, `logo_name`, `logo_image`, `created_at`) VALUES
(1, 'logo1', 'logoshop.jpg', '2024-12-16 04:33:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `menu_name` varchar(255) NOT NULL,
  `menu_link` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `menus`
--

INSERT INTO `menus` (`id`, `menu_name`, `menu_link`, `created_at`) VALUES
(1, 'Trang chủ', 'index.php', '2024-12-18 08:27:57'),
(6, 'Giới Thiệu', 'gioithieu.php', '2024-12-16 06:20:09'),
(7, 'Sản Phẩm', 'sanpham.php', '2024-12-16 06:20:24'),
(8, 'Dịch Vụ', 'dichvu.php', '2024-12-16 06:20:36'),
(9, 'Tin Tức', 'tintuc.php', '2024-12-16 06:20:53'),
(10, 'Liên Hệ', 'lienhe.php', '2024-12-16 06:21:02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `user_phone` varchar(15) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `messages`
--

INSERT INTO `messages` (`id`, `user_name`, `user_phone`, `note`, `created_at`) VALUES
(3, 'Lê văn Thắng', '0914476792', 'Cần hỗ trợ', '2024-12-18 09:19:01'),
(4, 'Lê văn Thắng', '0914476792', 'Cần hỗ trợ', '2024-12-18 09:19:30'),
(5, 'Lê văn Thắng', '0914476792', 'Cần hỗ trợ', '2024-12-18 09:21:22'),
(6, 'Lê văn Thắng', '0914476792', 'Cần hỗ trợ', '2024-12-18 09:27:26'),
(7, 'Lê văn Thắng', '0914476792', 'Cần hỗ trợ', '2024-12-18 09:27:46'),
(8, 'Lê văn Thắng', '0914476792', 'Cần hỗ trợ', '2024-12-18 09:28:57'),
(9, 'Lê văn Thắng', '0914476792', 'Cần hỗ trợ', '2024-12-18 09:29:08'),
(10, 'Lê văn Thắng', '0914476792', 'Cần hỗ trợ', '2024-12-18 09:30:09'),
(11, 'Lê văn Thắng', '0914476792', 'Cần hỗ trợ', '2024-12-18 09:31:14'),
(12, 'Lê văn Thắng', '0914476792', 'Cần hỗ trợ', '2024-12-18 09:33:25'),
(13, 'Trâm', '0397970507', 'Test', '2024-12-18 09:33:45'),
(14, 'Lê Văn A', '0914476791', 'Tele', '2024-12-18 09:36:47'),
(15, 'Lê văn Thắng', 's', 'sa', '2024-12-18 09:38:07'),
(16, 'Lê văn Thắng', 'fd', 'dfd', '2024-12-18 09:39:07'),
(17, 'Lê văn Thắng', 'dsad', 'ádasd', '2024-12-18 09:40:00'),
(18, 'Trâm', '0397970507', 'hỗ trợ', '2024-12-18 09:56:16'),
(19, 'Trâm', '0914476791', 'dsad', '2024-12-18 09:57:21');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `note` text DEFAULT NULL,
  `payment_method` varchar(255) NOT NULL,
  `cart_items` text NOT NULL,
  `order_status` varchar(50) DEFAULT 'Chưa xử lý',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `phone`, `email`, `address`, `note`, `payment_method`, `cart_items`, `order_status`, `created_at`, `total_price`) VALUES
(1, 1, 'Lê Văn Thắng', '0914476792', 'thang@gmail.com', 'thôn 3 số nhà 161', 'Ship nhanh', 'Online', '{\"2\":1,\"4\":3,\"1\":4,\"3\":4,\"5\":1}', 'Đã giao', '2024-12-16 09:36:27', 0.00),
(2, 1, 'Lê Văn Thắng', '0914476792', 'thang@gmail.com', 'thôn 3 số nhà 161', 'Ship nhanh', 'Online', 'Giỏ hàng trống', 'Đã giao', '2024-12-16 09:36:31', 0.00),
(3, 1, 'Lê Văn Thắng', '0914476792', 'thang@gmail.com', 'thôn 3 số nhà 161', 'Ship nhanh', 'Online', 'Giỏ hàng trống', 'Chưa xử lý', '2024-12-16 09:38:05', 0.00),
(4, 1, 'Lê Văn Thắng', '0914476792', 'thang@gmail.com', 'thôn 3 số nhà 161', 'Bọc kỹ giúp tôi', 'COD', '{\"3\":6}', 'Đã giao', '2024-12-17 09:27:08', 0.00),
(5, 1, 'Lê Văn Thắng 1', '09144444444', 'thang@gmail.com', 'thôn 3 số nhà 161', 'Mua Hàng lân 2', 'Online', '{\"2\":1,\"4\":1}', 'Đang xử lý', '2024-12-18 03:11:47', 0.00),
(6, 1, 'NGUYỄN THỊ NGỌC TRÂM', '0397970507', 'thang@gmail.com', '9', '1', 'COD', '{\"1\":2,\"3\":1}', 'Đã giao', '2024-12-18 03:14:57', 0.00),
(7, 1, 'Lê Văn Thắngdd', '0914476792', 'thang@gmail.com', 'thôn 3 số nhà 161', 'dsdasdas', 'COD', '{\"2\":1}', 'Đã hủy', '2024-12-18 03:19:29', 0.00),
(8, 1, 'sdsđ', '0914476792', 'thang@gmail.com', 'thôn 3 số nhà 161', 'sadsa', 'COD', 'null', 'Đã hủy', '2024-12-18 03:19:33', 0.00),
(9, 1, 'NGUYỄN THỊ NGỌC TRÂM', '0397970507', 'tram@gmail.com', '9', 'a', 'COD', '{\"8\":1,\"7\":2}', 'Đã giao', '2024-12-18 03:30:45', 227000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_current_price` decimal(10,2) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `product_description` text DEFAULT NULL,
  `product_detail` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `product_name`, `product_price`, `product_current_price`, `product_image`, `product_description`, `product_detail`) VALUES
(1, 'Bom mỹ', 200000.00, 180000.00, 'uploads/sp1.png', 'mô tả sản phẩm bom mỹ', 'nội dung sản phẩm bom mỹ'),
(2, 'Vải nhập khẩu', 80000.00, 60000.00, 'uploads/Screenshot_3.png', 'mô tả sản phẩm vải nhập khẩu', 'Nội dung sản phẩm vải nhập khẩu'),
(3, 'Táo nhập khẩu', 50000.00, 30000.00, 'uploads/Screenshot_2.png', 'mô tả sản phẩm táo nhập khẩu', 'Nội dung sản phẩm táo nhập khẩu'),
(4, 'Cà chua Đà Lạt', 100000.00, 80000.00, 'uploads/Screenshot_1.png', 'Mô tả sản phẩm cà chua đà lạt', 'Nội dung sản phẩm cà chua đà lạt'),
(5, 'Táo mỹ', 40000.00, 20000.00, 'uploads/Screenshot_3.png', 'Mô tả sản phẩm táo mỹ', 'Nội dung sản phẩm táo mỹ'),
(6, 'Táo Gala Pháp', 99000.00, 69000.00, 'uploads/tao_gala_phap_size_100_8aef2b957.jpg', 'Giao hàng nội thành 2 - 4 giờ', 'Đổi trả trong 48 giờ nếu sản phẩm không đạt chất lượng cam kết'),
(7, 'Vú sữa Bơ Hồng', 169000.00, 89000.00, 'uploads/vu_sua_bo_hong_df72d98f74b54c80b.jpg', 'Giao hàng nội thành 2 - 4 giờ\r\n', 'Đổi trả trong 48 giờ nếu sản phẩm không đạt chất lượng cam kết\r\n'),
(8, 'Thanh long ruột đỏ', 89000.00, 49000.00, 'uploads/thanh-long-ruot-do_c2ddd78ba6084.jpg', '- Xuất xứ: Việt Nam\r\n\r\n- Tiêu chuẩn chất lượng: Trái cây sạch\r\n\r\n- Trọng lượng sản phẩm: 1kg', 'Trái cây Việt canh tác an toàn, tuyển chọn loại nhất\r\nCó ruột đỏ, ngọt, giàu dinh dưỡng hơn thanh long trắng'),
(9, 'Ổi nữ hoàng', 49000.00, 32000.00, 'uploads/oi_1c8809c9f152475e880bd900f70db.jpg', '- Xuất xứ: Việt Nam \r\n', 'Ổi nữ hoàng có thịt quả khá dày và ruột rất nhỏ có một ít hạt.\r\n'),
(10, 'GRACEFUL 01', 300000.00, 279000.00, 'uploads/graceful_01_26450777607f4812ae53.jpg', 'Hộp trái cây màu hồng 23 x 23 x 8 cm \r\n', 'Nếu bạn chưa biết mua set quà 20/10 ở đâu tại TP.HCM. Bạn có thể tham khảo và đến với hệ thống của hàng Farmers Market để lựa chọn cho mình sản phẩm đạt chuẩn chất lượng, an tâm về nguồn gốc với giá thành tốt nhất.\r\n\r\n'),
(11, 'Táo Crips Red', 159000.00, 109000.00, 'uploads/tao_crips_red_nam_phi__kg___1__c.jpg', 'Chưa có mô tả cho sản phẩm này\r\n\r\n', 'Chưa có mô tả cho sản phẩm này\r\n\r\n'),
(12, 'Việt Quất New Zealand', 159000.00, 129000.00, 'uploads/viet_quat_new_zealand__hop_125g.jpg', 'Chưa có mô tả cho sản phẩm này\r\n\r\n', 'Chưa có mô tả cho sản phẩm này\r\n\r\n'),
(13, 'Táo Tàu (Hộp 200g)', 109000.00, 89000.00, 'uploads/tao_tau__hop_200g__dfc10e516b9d4.jpg', 'Chưa có mô tả cho sản phẩm này\r\n\r\n', 'Chưa có mô tả cho sản phẩm này\r\n\r\n'),
(14, 'Mận Tam Hoa (Kg)', 109000.00, 99000.00, 'uploads/mau__16__430acf17ad94458c8d003f3.jpg', 'Chưa có mô tả cho sản phẩm này\r\n\r\n', 'Chưa có mô tả cho sản phẩm này\r\n\r\n'),
(15, 'Sầu Riêng Ri6', 434700.00, 399000.00, 'uploads/sau_rieng_nguyen_trai_ri6_184b9f.jpg', 'Chưa có mô tả cho sản phẩm này\r\n\r\n', 'Chưa có mô tả cho sản phẩm này\r\n\r\n'),
(16, 'Lựu Ruby - 600 gr', 95000.00, 75000.00, 'uploads/luu_ruby_d6fdd43f0f894c0cb854e56.jpg', 'Chưa có mô tả cho sản phẩm này\r\n\r\n', 'Chưa có mô tả cho sản phẩm này\r\n\r\n');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `service_image` varchar(255) NOT NULL,
  `service_title` varchar(255) NOT NULL,
  `service_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `services`
--

INSERT INTO `services` (`id`, `service_image`, `service_title`, `service_description`) VALUES
(1, 'uploads/6760f32111cca.jpg', 'Chất Lượng Đảm Bảo', 'Mô tả chất lượng đảm bảo'),
(2, 'uploads/6760f33211fc9.jpg', 'Chất Lượng Đảm Bảo', 'Mô tả chất lượng đảm bảo'),
(3, 'uploads/6760f33ac54b5.jpg', 'Mô tả chất lượng đảm bảo', 'Mô tả chất lượng đảm bảo'),
(4, 'uploads/6760f33f7a692.jpg', 'Mô tả chất lượng đảm bảo', 'Mô tả chất lượng đảm bảo');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `slides`
--

CREATE TABLE `slides` (
  `id` int(11) NOT NULL,
  `slide_title` varchar(255) DEFAULT NULL,
  `slide_image` varchar(255) DEFAULT NULL,
  `slide_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `slides`
--

INSERT INTO `slides` (`id`, `slide_title`, `slide_image`, `slide_description`) VALUES
(1, 'sl1', 'uploads/banner1.png', 'sl1'),
(3, 'sl2', 'uploads/banner1.png', 'sl2');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `last_name`, `email`, `password`, `role`) VALUES
(1, '', '', '', 'user'),
(3, 'Admin', 'admin@gmail.com', 'admin', 'admin');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `access_logs`
--
ALTER TABLE `access_logs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `contact_info`
--
ALTER TABLE `contact_info`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `logos`
--
ALTER TABLE `logos`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `slides`
--
ALTER TABLE `slides`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `access_logs`
--
ALTER TABLE `access_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT cho bảng `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `contact_info`
--
ALTER TABLE `contact_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `logos`
--
ALTER TABLE `logos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `slides`
--
ALTER TABLE `slides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
