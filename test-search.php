<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Search API</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { background: #f5f5f5; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .result { background: white; padding: 10px; margin: 10px 0; border-left: 4px solid #4CAF50; }
        .error { border-left-color: #f44336; }
        input, select, button { padding: 8px; margin: 5px; }
        pre { background: #f0f0f0; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🔧 Test Search Functionality</h1>
    
    <div class="test-section">
        <h3>Manual API Test</h3>
        <form id="searchForm">
            <input type="text" id="searchInput" placeholder="Masukkan kata kunci pencarian..." style="width: 300px;">
            <select id="branchSelect">
                <option value="">Semua Cabang</option>
                <option value="jakarta">Jakarta</option>
                <option value="bandung">Bandung</option>
                <option value="surabaya">Surabaya</option>
                <option value="medan">Medan</option>
                <option value="makassar">Makassar</option>
            </select>
            <button type="submit">🔍 Test Search</button>
        </form>
        <div id="testResult"></div>
    </div>

    <div class="test-section">
        <h3>Automatic API Tests</h3>
        <button onclick="runAutoTests()">🚀 Run All Tests</button>
        <div id="autoTestResults"></div>
    </div>

    <script>
        document.getElementById('searchForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const search = document.getElementById('searchInput').value;
            const branch = document.getElementById('branchSelect').value;
            const resultDiv = document.getElementById('testResult');
            
            resultDiv.innerHTML = '<p>Testing...</p>';
            
            try {
                let url = 'api/shipments.php';
                const params = new URLSearchParams();
                
                if (search.trim()) params.append('search', search.trim());
                if (branch) params.append('cabang_tujuan', branch);
                
                if (params.toString()) url += '?' + params.toString();
                
                console.log('Testing URL:', url);
                
                const response = await fetch(url);
                const data = await response.json();
                
                resultDiv.innerHTML = `
                    <div class="result">
                        <strong>Status:</strong> ${response.status} ${response.ok ? '✅' : '❌'}<br>
                        <strong>URL:</strong> ${url}<br>
                        <strong>Results:</strong> ${Array.isArray(data) ? data.length + ' shipments' : 'Error'}<br>
                        <pre>${JSON.stringify(data, null, 2)}</pre>
                    </div>
                `;
                
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="result error">
                        <strong>Error:</strong> ${error.message}<br>
                        <pre>${error.stack}</pre>
                    </div>
                `;
            }
        });

        async function runAutoTests() {
            const resultsDiv = document.getElementById('autoTestResults');
            resultsDiv.innerHTML = '<p>Running tests...</p>';
            
            const tests = [
                { name: 'Get All Shipments', url: 'api/shipments.php' },
                { name: 'Search by "B"', url: 'api/shipments.php?search=B' },
                { name: 'Search by "Budi"', url: 'api/shipments.php?search=Budi' },
                { name: 'Filter Jakarta', url: 'api/shipments.php?cabang_tujuan=jakarta' },
                { name: 'Filter Bandung', url: 'api/shipments.php?cabang_tujuan=bandung' },
                { name: 'Search "SG" in Jakarta', url: 'api/shipments.php?search=SG&cabang_tujuan=jakarta' }
            ];
            
            let results = '<h4>Test Results:</h4>';
            
            for (const test of tests) {
                try {
                    const response = await fetch(test.url);
                    const data = await response.json();
                    
                    const success = response.ok && Array.isArray(data);
                    const count = Array.isArray(data) ? data.length : 0;
                    
                    results += `
                        <div class="result ${success ? '' : 'error'}">
                            <strong>${test.name}:</strong> ${success ? '✅' : '❌'} 
                            (${count} results)
                            <br><small>${test.url}</small>
                        </div>
                    `;
                    
                } catch (error) {
                    results += `
                        <div class="result error">
                            <strong>${test.name}:</strong> ❌ Error: ${error.message}
                            <br><small>${test.url}</small>
                        </div>
                    `;
                }
            }
            
            resultsDiv.innerHTML = results;
        }
    </script>

    <hr>
    <h3>🔗 Quick Links</h3>
    <a href="index.php">📱 Main Application</a> | 
    <a href="setup.php">🚀 Database Setup</a> | 
    <a href="test-connection.php">🔧 Connection Test</a>
</body>
</html>