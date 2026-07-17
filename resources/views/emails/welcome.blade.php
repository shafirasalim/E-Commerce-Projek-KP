<x-mail::message>
Halo, {{ $user->name }}!

Terima kasih telah bergabung di Cianjur Fresh. Kami senang Anda memilih kami untuk kebutuhan produk segar Anda.

Silakan mulai berbelanja untuk melihat koleksi produk terbaru kami.

<x-mail::button :url="route('shop.index')">
Mulai Belanja
</x-mail::button>

Salam,
Tim Cianjur Fresh
</x-mail::message>