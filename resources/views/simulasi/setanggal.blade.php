<form method="POST" action="/simulasi/tanggal">
    @csrf
    <input type="date" name="tanggal" required>
    <button type="submit">Set Simulasi Tanggal</button>
</form>

<form method="POST" action="/simulasi/reset">
    @csrf
    <button type="submit">Reset Tanggal</button>
</form>
