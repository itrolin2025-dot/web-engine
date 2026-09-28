<!DOCTYPE html>
<html lang="id">

@php
    // Preview mode: tanpa customers_website — semua variabel null-safe
    $navContent = $navbarPresets ?? [];
    $logoFile = $navContent['image'] ?? null;
    $favicon = null;
    // Folder blade template. Kolom template.path tersimpan lengkap (mis. "template.jolie"),
    // jadi pastikan prefix "template." dan fallback ke jolie bila kosong.
    $tplPath = $template->path ?: 'template.jolie';
    if (!str_starts_with($tplPath, 'template.')) {
        $tplPath = 'template.' . $tplPath;
    }
    $tplFolder = substr($tplPath, strlen('template.'));
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    @if($favicon)
        <link rel="icon" type="image/png" href="{{ asset($favicon) }}">
    @else
        <link rel="icon" href="data:,">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">
</head>

<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --coral: #FF9B7A;
            --coral-dark: #E8876A;
            --peach: #FFB8A0;
            --pink: #FFC4D6;
            --lavender: #C4B5FD;
            --mint: #A7F3D0;
            --sky: #BAE6FD;
            --cream: #FFF5F0;
            --dark: #2D2D2D;
            --gray: #6B7280;
            --light-gray: #F3F4F6;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            line-height: 1.6;
            overflow-x: hidden;
            /* font-family: 'Plus Jakarta Sans', sans-serif; */
            background-color: #FCFBFA;
            color: #2C2A29;
        }

        .font-serif-brand {
            font-family: 'Playfair Display', serif;
        }

        h1,
        h2,
        h3,
        h4 {
            font-family: 'Playfair Display', serif;
        }

        /* ===== FLOATING BUTTONS ===== */
        .floating-actions {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            display: flex;
            gap: 1rem;
            z-index: 1000;
        }

        .fab {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, background-color 0.3s, opacity 0.3s;
            cursor: pointer;
            border: none;
        }

        .fab:hover {
            transform: translateY(-5px);
        }

        .fab-wa {
            background-color: #25D366;
            color: white;
        }

        .fab-wa:hover {
            background-color: #128C7E;
        }

        .fab-top {
            background-color: var(--dark);
            font-size: 1.2rem;
            opacity: 0;
            pointer-events: none;
        }

        .fab-top.visible {
            opacity: 1;
            pointer-events: auto;
        }

        /* Broken image styling */
        img {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }

        img::before {
            content: "Image";
        }
    </style>
</head>

<body>

    {{-- Preview: section diambil dari table templates_section (via template),
         content = default value dari templates_sections_content.
         Blade section di-resolve dari folder template asli (template.{path}.{slug}). --}}
    @foreach($layouts as $layout)
        @php
            $renderedSection = null;
            $sectionError = null;
            try {
                $renderedSection = view('template.' . $tplFolder . '.' . $layout->slug, ['layout' => $layout])->render();
            } catch (\Throwable $e) {
                $renderedSection = null;
                $sectionError = $e->getMessage();
            }
        @endphp
        @if($renderedSection !== null)
            {!! $renderedSection !!}
        @else
            {{-- Section gagal render / blade belum ada: placeholder ringan --}}
            <section class="py-16 px-4 text-center" style="background-color: #f8f8f8;">
                <p class="text-xs uppercase tracking-widest text-gray-400">Section</p>
                <h2 class="text-xl font-semibold text-gray-600 mt-1">{{ $layout->name }}</h2>
                @if($sectionError)
                    <!-- Preview error ({{ $layout->slug }}): {{ $sectionError }} -->
                @endif
            </section>
        @endif
    @endforeach

    <!-- FLOATING BUTTONS -->
    <div class="floating-actions">
        <button class="fab fab-top" id="backToTop" aria-label="Back to top">
            &#8593;
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var backToTop = document.getElementById('backToTop');
            if (backToTop) {
                backToTop.addEventListener('click', function() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }
        });
    </script>

    <script>
        function scrollToSection(event, sectionId) {
            // Mencegah URL berubah atau menambahkan tanda #
            event.preventDefault(); 
            
            // Melakukan scroll secara mulus ke elemen tujuan
            const targetElement = document.getElementById(sectionId);
            if (targetElement) {
                targetElement.scrollIntoView({ behavior: 'smooth' });
            }
        }
     </script>
</body>

</html>
