(function () {
  // Attach to all add-to-cart forms
  function handleSubmit(e) {
    const form = e.target;

    // Only guard forms meant for add-to-cart
    if (!form.classList.contains('add-to-cart-form')) return;

    // Read stock from data attribute (preferred) or hidden input fallback
    const stockAttr = form.getAttribute('data-stock');
    const stockHidden = form.querySelector('input[name="stock"]');
    const stock = parseInt(stockAttr != null ? stockAttr : (stockHidden ? stockHidden.value : ''), 10);

    // Quantity: use input[name="quantity"] if present, else default to 1
    const qtyInput = form.querySelector('input[name="quantity"]');
    const qty = parseInt(qtyInput && qtyInput.value ? qtyInput.value : '1', 10);

    // Basic guards
    if (isNaN(stock)) {
      // If stock not provided, let server decide (don’t block)
      return;
    }

    if (stock <= 0) {
      e.preventDefault();
      alert('Out of stock. This item cannot be added to the cart.');
      return;
    }

    if (isNaN(qty) || qty < 1) {
      e.preventDefault();
      alert('Please enter a valid quantity (at least 1).');
      if (qtyInput) qtyInput.focus();
      return;
    }

    if (qty > stock) {
      e.preventDefault();
      alert('Only ' + stock + ' left in stock. Please adjust your quantity.');
      if (qtyInput) qtyInput.focus();
      return;
    }

    // Optionally, disable submit to avoid double-clicks
    const btn = form.querySelector('button[type="submit"], input[type="submit"]');
    if (btn) {
      btn.disabled = true;
      // Re-enable after a short delay in case navigation is blocked
      setTimeout(function(){ btn.disabled = false; }, 4000);
    }
  }

  // Use event capture on the document to catch all form submits
  document.addEventListener('submit', handleSubmit, true);
})();
