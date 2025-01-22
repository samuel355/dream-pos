document.addEventListener('DOMContentLoaded', function() {
  initializeDateRangeFilter();
  loadSales();
  
  if (typeof feather !== 'undefined') {
      feather.replace();
  }
});

function initializeDateRangeFilter() {
  const dateRange = document.getElementById('dateRange');
  const customDates = document.querySelectorAll('.custom-dates');
  
  dateRange.addEventListener('change', function() {
      const showCustomDates = this.value === 'custom';
      customDates.forEach(elem => {
          elem.style.display = showCustomDates ? 'block' : 'none';
      });
  });
}

function loadSales() {
  const dateRange = document.getElementById('dateRange').value;
  const startDate = document.getElementById('startDate')?.value || '';
  const endDate = document.getElementById('endDate')?.value || '';

  // Show loading state
  document.querySelector('#salesTable tbody').innerHTML = `
      <tr>
          <td colspan="6" class="text-center">Loading...</td>
      </tr>
  `;

  fetch('php/get-sales.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
      },
      body: JSON.stringify({
          dateRange: dateRange,
          startDate: startDate,
          endDate: endDate
      })
  })
  .then(response => response.json())
  .then(data => {
      console.log('Response:', data); // For debugging
      if (data.status === 'success') {
          updateSalesDisplay(data.data);
      } else {
          toastr.error(data.message || 'Error loading sales data');
          // Show error in table
          document.querySelector('#salesTable tbody').innerHTML = `
              <tr>
                  <td colspan="6" class="text-center text-danger">
                      ${data.message || 'Error loading sales data'}
                  </td>
              </tr>
          `;
      }
  })
  .catch(error => {
      console.error('Error:', error);
      toastr.error('Error loading sales data');
      // Show error in table
      document.querySelector('#salesTable tbody').innerHTML = `
          <tr>
              <td colspan="6" class="text-center text-danger">
                  Error loading sales data. Please try again.
              </td>
          </tr>
      `;
  });
}

function updateSalesDisplay(data) {
  // Update summary cards
  document.querySelector('.sales-total').textContent = formatCurrency(data.total_sales || 0);
  document.querySelector('.orders-count').textContent = data.total_orders || 0;
  document.querySelector('.customers-count').textContent = data.total_customers || 0;
  document.querySelector('.avg-sale').textContent = formatCurrency(data.average_sale || 0);

  // Update sales table
  const tbody = document.querySelector('#salesTable tbody');
  
  if (!data.sales || data.sales.length === 0) {
      tbody.innerHTML = `
          <tr>
              <td colspan="6" class="text-center">No sales found for the selected period</td>
          </tr>
      `;
      return;
  }

  tbody.innerHTML = data.sales.map(sale => `
      <tr>
          <td>#${sale.id}</td>
          <td>${formatDate(sale.created_at)}</td>
          <td>${escapeHtml(sale.customer_name)}</td>
          <td>${escapeHtml(sale.items || 'No items')}</td>
          <td>${formatCurrency(sale.total_amount)}</td>
          <td>
              <button class="btn btn-sm btn-primary" onclick="viewSaleDetails(${sale.id})">
                  <i data-feather="eye"></i>
              </button>
              <button class="btn btn-sm btn-secondary" onclick="printReceipt(${sale.id})">
                  <i data-feather="printer"></i>
              </button>
          </td>
      </tr>
  `).join('');

  // Reinitialize Feather icons
  if (typeof feather !== 'undefined') {
      feather.replace();
  }
}

function formatCurrency(amount) {
  return 'GHS ' + parseFloat(amount || 0).toFixed(2);
}

function formatDate(dateString) {
  if (!dateString) return '';
  return new Date(dateString).toLocaleString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
  });
}

function escapeHtml(str) {
  if (!str) return '';
  const div = document.createElement('div');
  div.textContent = str;
  return div.innerHTML;
}

function viewSaleDetails(saleId) {
  window.location.href = `order-details.php?id=${saleId}`;
}

function printReceipt(saleId) {
  window.open(`print-receipt.php?id=${saleId}`, '_blank');
}