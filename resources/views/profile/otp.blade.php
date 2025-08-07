<form action="{{ route('profile.submit.otp') }}" method="POST" class="max-w-sm mx-auto bg-white p-6 rounded-2xl shadow-md space-y-4">
    @csrf
    <h2 class="text-xl font-semibold text-center text-gray-800">Verifikasi OTP</h2>
    <p class="text-sm text-center text-gray-500">Masukkan 6 digit kode OTP yang dikirim ke WhatsApp kamu</p>

    <input
        type="text"
        name="otp"
        maxlength="6"
        placeholder="Kode OTP"
        required
        class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-center text-lg tracking-widest"
    >

    <button
        type="submit"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition duration-200"
    >
        Verifikasi
    </button>

    @if (session('gagal'))
        <div class="text-red-600 text-sm text-center">{{ session('gagal') }}</div>
    @endif

    @if (session('sukses'))
        <div class="text-green-600 text-sm text-center">{{ session('sukses') }}</div>
    @endif
</form>
