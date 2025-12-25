# 📚 Truyện Hay TT - Hệ Thống Đọc Truyện Trực Tuyến

**Truyện Hay TT** là một ứng dụng web đọc truyện trực tuyến được xây dựng bằng ngôn ngữ PHP, cung cấp trải nghiệm đọc truyện mượt mà cho người dùng và bộ công cụ quản lý nội dung chuyên nghiệp cho quản trị viên.

---

## ✨ Tính Năng Chính

### 📖 Dành cho Người Dùng
- **Trang chủ**: Hiển thị danh sách truyện mới, truyện HOT với khả năng phân trang.
- **Khám phá**: Phân loại truyện theo nhiều thể loại khác nhau (Tiên Hiệp, Ngôn Tình, Kiếm Hiệp, Trọng Sinh, ...).
- **Tìm kiếm**: Hệ thống tìm kiếm truyện theo tên nhanh chóng.
- **Đọc truyện**: Giao diện đọc chương truyện rõ ràng, dễ theo dõi.
- **Tài khoản cá nhân**:
  - Đăng ký và Đăng nhập thành viên.
  - Theo dõi danh sách truyện yêu thích.
  - Quản lý lịch sử đọc truyện.
- **Tương tác**: Cho phép người dùng để lại bình luận và đánh giá các bộ truyện.

### 🛠️ Dành cho Quản Trị Viên (Admin)
- **Thống kê tổng quan**: Theo dõi hoạt động của website thông qua các số liệu thống kê.
- **Quản lý nội dung**:
  - **Quản lý truyện**: Thêm mới, cập nhật thông tin và chương cho các bộ truyện.
  - **Quản lý thể loại**: Linh hoạt trong việc thay đổi và cập nhật danh sách thể loại.
- **Quản lý cộng đồng**:
  - Quản lý danh sách người dùng thành viên.
  - Kiểm duyệt và quản lý các bình luận trên hệ thống.

---

## 🚀 Công Nghệ Sử Dụng

- **Backend**: PHP (Sử dụng MySQLi để kết nối cơ sở dữ liệu)
- **Cơ sở dữ liệu**: MySQL
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Môi trường phát triển**: XAMPP / WAMP / Laragon

---

## 📁 Cấu Trúc Thư Mục

Dự án được tổ chức một cách rõ ràng:

- `web/`: Thư mục chính chứa mã nguồn PHP của ứng dụng.
  - `admin/`: Module quản trị (Admin Panel).
  - `database/`: Chứa các file xử lý logic truy vấn dữ liệu (Model).
  - `frontend/`: Chứa các thành phần giao diện.
- `public/`: Thư mục chứa các tài nguyên tĩnh (Assets).
  - `anhlogo.png`: Logo của website.
  - `timkiem.png`: Icon tìm kiếm.
  - Các hình ảnh bìa truyện khác.

---

## 🛠️ Hướng Dẫn Cài Đặt

Để chạy dự án này trên máy tính cá nhân, bạn có thể thực hiện theo các bước sau:

1.  **Chuẩn bị môi trường**:
    - Cài đặt phần mềm tạo server local như **XAMPP**.
2.  **Tải mã nguồn**:
    - Tải hoặc clone thư mục dự án vào thư mục `htdocs` của XAMPP (thông thường là `C:\xampp\htdocs\`).
3.  **Thiết lập Cơ sở dữ liệu**:
    - Khởi động **Apache** và **MySQL** từ XAMPP Control Panel.
    - Truy cập `localhost/phpmyadmin`.
    - Tạo một cơ sở dữ liệu mới với tên: `truyenhaytt`.
    - Nhập (Import) file SQL của dự án (nếu có) vào cơ sở dữ liệu vừa tạo.
4.  **Cấu hình kết nối**:
    - Kiểm tra và chỉnh sửa file `web/database.php` nếu thông tin kết nối MySQL của bạn khác với mặc định (User: `root`, Password: "").
5.  **Truy cập ứng dụng**:
    - Mở trình duyệt và truy cập đường dẫn:
      ```
      http://localhost/baithiphpcb/webtruyen/web/Trangchu.php
      ```

---

⚡ *Dự án được phát triển nhằm mục đích học tập và xây dựng ứng dụng đọc truyện cơ bản.*
