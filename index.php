<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Buy USDT with AED</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body { font-family: sans-serif; background: #0b0c10; color: white; margin: 0; padding: 20px; }
    .container { max-width: 500px; margin: auto; background: #1f2833; padding: 30px; border-radius: 10px; }
    input, button, select {
      width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; border: none;
      font-size: 1em;
    }
    button { background-color: #f1c40f; color: black; cursor: pointer; font-weight: bold; }
    h1, label { margin-top: 15px; }
    @media (max-width: 600px) {
      .container { padding: 20px; }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Buy USDT with AED</h1>

    <label for="aed">Amount in AED:</label>
    <input type="number" id="aed" placeholder="Enter AED" />

    <label for="usdt">You Receive (USDT):</label>
    <input type="text" id="usdt" readonly />

    <label for="method">Payment Method:</label>
    <select id="method">
      <option value="bank">Bank Transfer</option>
      <option value="card">Card</option>
    </select>

    <button onclick="buy()">Buy USDT</button>
    <p id="status"></p>
  </div>

  <script>
    let rate = 0;

    async function getRate() {
      const res = await fetch('https://api.coingecko.com/api/v3/simple/price?ids=tether&vs_currencies=aed');
      const data = await res.json();
      rate = data.tether.aed;
    }

    async function convert() {
      if (!rate) await getRate();
      const aed = document.getElementById("aed").value;
      const usdt = (aed / rate).toFixed(2);
      document.getElementById("usdt").value = isNaN(usdt) ? "" : usdt;
    }

    document.getElementById("aed").addEventListener("input", convert);

    async function buy() {
      await getRate();
      const aed = document.getElementById("aed").value;
      const usdt = document.getElementById("usdt").value;
      const method = document.getElementById("method").value;

      if (!aed || !usdt) return alert("Enter a valid AED amount.");

      const formData = new FormData();
      formData.append("aed", aed);
      formData.append("usdt", usdt);
      formData.append("method", method);

      const res = await fetch("save_transaction.php", {
        method: "POST",
        body: formData,
      });

      const result = await res.text();
      if (result === "success") {
        window.location.href = "success.php";
      } else {
        document.getElementById("status").innerText = result;
      }
    }

    getRate(); // Initial rate fetch
  </script>
</body>
</html>
