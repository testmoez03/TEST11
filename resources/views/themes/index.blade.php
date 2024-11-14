<!DOCTYPE html>
<html>
<head>
    <title>Subdomain Deployment</title>
</head>
<body>
    <h1>Deploy a Theme</h1>
    <form method="POST" action="/deploy">
        @csrf
        <label for="theme">Select Theme:</label>
        <select name="theme">
            @foreach($themes as $theme)
                <option value="{{ $theme }}">{{ $theme }}</option>
            @endforeach
        </select>
        <label for="subdomain">Subdomain:</label>
        <input type="text" name="subdomain" placeholder="theme1" required>
        <button type="submit">Deploy</button>
    </form>

    <h2>Existing Subdomains</h2>
    <ul>
        @foreach($subdomains as $subdomain)
            <li>{{ $subdomain->subdomain }} - {{ $subdomain->theme }}</li>
        @endforeach
    </ul>
</body>
</html>
