const checkboxes = document.querySelectorAll('.filters input[type="checkbox"]');
    const cards = document.querySelectorAll('.card');
    const clearBtn = document.getElementById('clearFilters');

    function applyFilters() {
      let activeFilters = { duration: [], level: [], price: [] };

      checkboxes.forEach(c => {
        if (c.checked) {
          if (['1month','3months','6months','9months','1year'].includes(c.value)) {
            activeFilters.duration.push(c.value);
          } else if (['basic','gold','premium','vip'].includes(c.value)) {
            activeFilters.level.push(c.value);
          } else {
            activeFilters.price.push(c.value);
          }
        }
      });

      cards.forEach(card => {
        let show = true;

        if (activeFilters.duration.length && !activeFilters.duration.includes(card.dataset.duration)) {
          show = false;
        }
        if (activeFilters.level.length && !activeFilters.level.includes(card.dataset.level)) {
          show = false;
        }
        if (activeFilters.price.length && !activeFilters.price.includes(card.dataset.price)) {
          show = false;
        }

        card.style.display = show ? 'flex' : 'none';
      });
    }

    checkboxes.forEach(cb => cb.addEventListener('change', applyFilters));

    clearBtn.addEventListener('click', () => {
      checkboxes.forEach(cb => cb.checked = false);
      applyFilters();
    });