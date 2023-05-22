<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .footer {
            background-color: #f9fafb;
            color: #4a5568;
        }

        .footer-logo {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .footer-link {
            font-size: 0.875rem;
            text-decoration: none;
            color: #4a5568;
            transition: color 0.3s ease;
        }

        .footer-link:hover {
            color: #1a202c;
        }

        .footer-social-icon {
            font-size: 1.25rem;
            color: #4a5568;
            transition: color 0.3s ease;
        }

        .footer-social-icon:hover {
            color: #1a202c;
        }
    </style>
</head>
<body>
    <div class="bottom-0 fixed w-[100%]">
        <footer class="footer pb-20 lg:pb-0 md-pb-0 text-center">
            <div class="mx-auto w-full container p-4 sm:p-6">
                <div class="md:flex md:justify-between">
                    <div class="mb-6 md:mb-0 mr-5 flex justify-center" data-aos="fade-up">
                        <a href="https://hathibrand.in" class="flex items-center">
                            <img src="./assets/Logo/Favicon.ico" class="h-8 mr-3" alt="Logo" />
                            <span class="self-center footer-logo">Hathibrand</span>
                        </a>
                    </div>
                    <div class="grid grid-cols-3 gap-8 sm:gap-6 sm:grid-cols-3" data-aos="fade-up">
                        <div>
                            <a href="/about" class="mb-6 text-sm font-semibold uppercase footer-link">About</a>
                        </div>
                        <div>
                            <a href="/contact" class="mb-6 text-sm font-semibold uppercase footer-link">Contact</a>
                        </div>
                        <div>
                            <a href='https://aniketparmar29.github.io/' target='_blank' rel='noreferrer' class="mb-6 text-sm font-semibold uppercase footer-link">More</a>
                        </div>
                    </div>
                </div>
                <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
                <div class="sm:flex sm:items-center sm:justify-between">
                    <span class="text-sm  sm:text-center">© 2023 <a href="https://hathibrand.in/" class="hover:underline footer-link">Hathibrand</a>. All Rights Reserved.
                    </span>
                    <div class="flex mt-4 space-x-6 sm:justify-center sm:mt-0 justify-center">
                        <a href="https://www.facebook.com/hathibrandagarbatti" class="footer-social-icon" target="_blank" rel="noreferrer">
<i class="fab fa-facebook"></i>
<span class="sr-only">Facebook page</span>
</a>
<a href="https://www.instagram.com/hathibrandagarbatti/" class="footer-social-icon" target="_blank" rel="noreferrer">
<i class="fab fa-instagram"></i>
<span class="sr-only">Instagram page</span>
</a>
<a href="https://wa.me/+919638857089" class="footer-social-icon" target="_blank" rel="noreferrer">
<i class="fab fa-whatsapp"></i>
<span class="sr-only">WhatsApp</span>
</a>
<a href="https://mail.google.com/mail/u/0/#inbox?compose=GTvVlcSKjRMwtVxgDTJbPNTJVFMdSnTxkxfwSHDLZbhmtgGsNMHnxZHjpQsDnqVjTGnlhTgPDKmpg" class="footer-social-icon" target="_blank" rel="noreferrer">
<i class="fas fa-envelope"></i>
<span class="sr-only">Email</span>
</a>
</div>
</div>
</div>
</footer>
</div>

</body>
</html>
