<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Kur Gösterimi</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function updateCurrencyData(currencyId, data) {
            const element = document.getElementById(currencyId);
            element.querySelector('.value').textContent = data.value;
            const changeElement = element.querySelector('.change');
            changeElement.textContent = data.change;
            changeElement.className = `change ${data.direction}`; // Classları güncelle
        }

        function updateData() {
            fetch('api.php')
                .then(response => response.json())
                .then(data => {
                    updateCurrencyData('bist', data.BIST);
                    updateCurrencyData('euro', data.EURO);
                    updateCurrencyData('dolar', data.DOLAR);
                    updateCurrencyData('altin', data.ALTIN);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    alert('Veri alınırken bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
                });
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateData(); // Initial load
            setInterval(updateData, 10000); // Update every 10 seconds
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="currency-card" id="bist">
            <h2>BIST</h2>
            <p class="value">0.00</p>
            <p class="change">0.00%</p>
        </div>
        <div class="currency-card" id="euro">
            <h2>EURO</h2>
            <p class="value">0.00</p>
            <p class="change">0.00%</p>
        </div>
        <div class="currency-card" id="dolar">
            <h2>DOLAR</h2>
            <p class="value">0.00</p>
            <p class="change">0.00%</p>
        </div>
        <div class="currency-card" id="altin">
            <h2>ALTIN</h2>
            <p class="value">0.00</p>
            <p class="change">0.00%</p>
        </div>
    </div>
</body>
</html>