<!DOCTYPE html>
<html>
<head>
    <title>Debug Login</title>
</head>
<body>
    <h2>Debug Admin Data</h2>
    
    @php
        $admins = \App\Models\Admin::all();
    @endphp
    
    <h3>Data Admin di Database:</h3>
    <table border="1">
        <tr>
            <th>NRP Admin</th>
            <th>Username</th>
            <th>Password (Hash)</th>
            <th>Nama Admin</th>
        </tr>
        @foreach($admins as $admin)
        <tr>
            <td>{{ $admin->NRP_admin }}</td>
            <td>{{ $admin->username }}</td>
            <td>{{ $admin->password }}</td>
            <td>{{ $admin->nama_admin }}</td>
        </tr>
        @endforeach
    </table>
    
    <hr>
    
    <h3>Test Login Manual:</h3>
    <form method="POST" action="{{ route('debug.login') }}">
        @csrf
        Username: <input type="text" name="username" value="admin"><br><br>
        Password: <input type="text" name="password" value="admin123"><br><br>
        <button type="submit">Test Login</button>
    </form>
    
    @if(session('debug'))
        <h3>Debug Results:</h3>
        <pre>{{ session('debug') }}</pre>
    @endif
</body>
</html>