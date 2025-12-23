# Design System v2 - Tuyến Droppii

## Thay đổi chính
- Palette tinh gọn (primary, accent, surface).
- Fluid typography (hero / xxl / xl / lg / md / sm / xs).
- Spacing scale chuẩn hóa (1–16).
- Card & grid đồng bộ (product-card, feature-card).
- FAQ dùng `<details class="faq-item"><summary>...</summary>...</details>`.
- JS tự động thêm aria-expanded (common.js).
- Form trạng thái focus rõ ràng + shadow accessibility.
- QR widget tối giản.
- Footer giảm nhiễu trực quan.

## Sử dụng
```html
<link rel="stylesheet" href="styles.css">
<script src="common.js" defer></script>
```

### Hero
```html
<header class="hero-header">
  <div class="container hero-content">
     <div class="hero-text">
        <h1>Tiêu đề chính</h1>
        <p class="subtitle">Mô tả ngắn gọn...</p>
        <a class="btn btn-primary">Hành động</a>
     </div>
  </div>
</header>
```

### Grid sản phẩm
```html
<div class="grid-products">
  <div class="product-card">
    <img src="..." alt="">
    <h3>Tên sản phẩm</h3>
    <p>Mô tả ngắn</p>
    <div class="price">Liên hệ</div>
    <div class="actions">
      <a class="btn btn-soft">Chi tiết</a>
      <a class="btn btn-primary">Tư vấn</a>
    </div>
  </div>
</div>
```

### FAQ
```html
<details class="faq-item">
  <summary>Hỏi: Máy có bảo hành?</summary>
  <div class="faq-answer">Có, bảo hành chính hãng...</div>
</details>
```

## Token tham khảo
Xem đầu `styles.css` để cập nhật màu và scale.

## Không nên
- Thêm inline style trừ khi cần (thử nghiệm).
- Sao chép CSS cũ đã loại bỏ.
- Override biến gốc mà không có lý do.

## Tiếp theo
- Tối ưu ảnh WEBP.
- Thêm dark mode (CSS prefers-color-scheme).
- Gom nhóm JS form xử lý (sau).

Hỗ trợ: xem `common.js` hoặc liên hệ dev.

