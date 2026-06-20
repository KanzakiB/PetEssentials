<nav class="bg-[#FD9596] text-white fixed top-0 left-0 w-full h-16 z-50 shadow-lg">
    <div class="container mx-auto flex justify-between items-center h-full px-4">

        <a href="">
            <img src="images\logo.png" alt="The Happy Tails Logo" class="h-10">
        </a>

        <div class="flex items-center space-x-6">
            <div class="relative">
                <button class="text-white hover:text-gray-200 focus:outline-none" id="inbox-button">
                    <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M20 2H4a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm-1 17H5V6h14v13zM7 10h10v2H7v-2z" />
                    </svg>
                </button>
                <div class="absolute right-0 mt-2 w-64 bg-white text-gray-800 rounded-md shadow-lg z-10 hidden" id="inbox-menu">
                    <div class="p-4">
                        <h3 class="text-lg font-bold mb-2">Inbox</h3>
                        <p class="text-center text-sm text-gray-500 py-4">No messages yet</p>
                        <a href="messages.php" class="block text-center py-2 text-blue-600 hover:underline text-sm">
                            View All Messages
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    const inboxButton = document.getElementById('inbox-button');
    const inboxMenu = document.getElementById('inbox-menu');

    inboxButton.addEventListener('click', (e) => {
        e.stopPropagation();
        inboxMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
        if (!inboxMenu.classList.contains('hidden') && !inboxMenu.contains(e.target)) {
            inboxMenu.classList.add('hidden');
        }
    });
</script>
