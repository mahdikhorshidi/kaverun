# Kaverun — Scroll-Driven Hero Section

هرو سکشن اسکرول‌محور برای وردپرس بر پایه‌ی فریم‌های استخراج‌شده از ویدئوی مینی
بیل مکانیکی. کل استایل و اسکریپت داخل یک فایل HTML مستقل قرار دارد و هیچ
وابستگی خارجی (CDN، فونت، jQuery، GSAP، ...) ندارد.

## فایل‌ها

| مسیر | توضیح |
|---|---|
| `kaverun-hero-section.html` | تکه‌کد قابل Paste در «Custom HTML» وردپرس. |
| `hero-frames/desktop/` | ۶۰ فریم WebP با ابعاد 1280×720 (کل ~1.7MB). |
| `hero-frames/mobile/` | ۶۰ فریم WebP با ابعاد 720×405 برای موبایل (~840KB). |
| `hero-frames.zip` | آرشیو فشرده‌ی کل پوشه‌ی `hero-frames` برای آپلود آسان. |

## نصب روی وردپرس

1. `hero-frames.zip` را روی هاست آپلود و در ریشه (public_html) اکسترکت کنید تا
   ساختار زیر ایجاد شود:
   ```
   public_html/
   ├── hero-frames/
   │   ├── desktop/frame_001.webp … frame_060.webp
   │   └── mobile/frame_001.webp  … frame_060.webp
   ```
2. صفحه‌ی مورد نظر را در وردپرس ویرایش کنید و یک بلوک **Custom HTML** بالای بقیه
   محتوا اضافه کنید.
3. کل محتوای `kaverun-hero-section.html` را در آن بلوک Paste کنید و ذخیره کنید.

اگر پوشه را در مسیر دیگری (مثلاً `/wp-content/uploads/hero-frames/`) گذاشتید،
فقط دو اتربیوت `data-frames-desktop` و `data-frames-mobile` روی تگ `<section>`
را اصلاح کنید.

## بهینه‌سازی‌ها

- **LCP**: اولین فریم با `fetchpriority="high"` به عنوان `<img>` رندر می‌شود.
- **CLS**: کانتینر ثابت با ابعاد صریح، هیچ layout shift رخ نمی‌دهد.
- **INP**: رویداد اسکرول به صورت passive + `requestAnimationFrame`.
- **بارگذاری تدریجی**: ۸ فریم اول در ابتدا، بقیه با `requestIdleCallback` در
  پس‌زمینه.
- **Responsive**: روی موبایل به صورت خودکار فریم‌های سبک‌تر بارگذاری می‌شوند.
- **Accessibility**: پشتیبانی `prefers-reduced-motion`، متن `alt` معنادار،
  فوکوس کیبوردی.
- **Fallback**: مرورگرهای بدون JS اولین فریم را به صورت تصویر ثابت می‌بینند.

## تنظیمات قابل‌تغییر (روی تگ `<section>`)

| اتربیوت | مقدار پیش‌فرض | توضیح |
|---|---|---|
| `data-frame-count` | `60` | تعداد فریم‌ها |
| `data-frames-desktop` | `/hero-frames/desktop/` | مسیر فریم‌های دسکتاپ |
| `data-frames-mobile` | `/hero-frames/mobile/` | مسیر فریم‌های موبایل |

طول (و در نتیجه سرعت) انیمیشن با متغیر CSS `--kv-hero-scroll` کنترل می‌شود:

- پیش‌فرض دسکتاپ: `500vh`
- پیش‌فرض موبایل: `380vh`
- بزرگ‌تر → آرام‌تر و اسکرول طولانی‌تر
- کوچک‌تر → سریع‌تر و اسکرول کوتاه‌تر

برای تغییر می‌توانید یک قطعه CSS ساده بعد از سکشن اضافه کنید:

```html
<style>#kv-hero{--kv-hero-scroll:700vh}</style>
```
