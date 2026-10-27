/* 
B20250035 Alya Sofiya binti Haji Rosaimi
B20250113 Nur Aini Jamilah Mohamad Johnny
B20250176 Ahmad Ridhuan bin Mohamad Johnny */

function toggleCustomInput() {
    var dropdown = document.getElementById("status");
    var customContainer = document.getElementById("customNameContainer");
    var customInput = document.getElementById("customName");
  
    if (dropdown && dropdown.value === "custom") {
      customContainer.style.display = "block";
      customInput.required = true;
    } else if (customContainer && customInput) {
      customContainer.style.display = "none";
      customInput.required = false;
      customInput.value = "";
    }
  }
  
  function switchTab(tabId, evt) {
      const buttons = document.querySelectorAll('.tab-btn');
      buttons.forEach(btn => btn.classList.remove('active'));
  
      const panels = document.querySelectorAll('.tab-panel');
      panels.forEach(panel => panel.classList.remove('active'));
  
      const selectedPanel = document.getElementById(tabId);
      if (selectedPanel) {
          selectedPanel.classList.add('active');
      }
      
      if (evt && evt.currentTarget) {
          evt.currentTarget.classList.add('active');
      } else if (window.event && window.event.currentTarget) {
          window.event.currentTarget.classList.add('active');
      }
  
      const selectedPanelItems = selectedPanel ? selectedPanel.querySelectorAll('.book-card').length : 0;
      const titleCountEl = document.getElementById('title-count');
      if (titleCountEl) {
          titleCountEl.textContent = `${selectedPanelItems} Titles`;
      }
  }
  
  function switchAuthTab(panelId, evt) {
      const buttons = document.querySelectorAll('.auth-tab-btn');
      buttons.forEach(btn => btn.classList.remove('active'));
  
      const panels = document.querySelectorAll('.auth-tab-panel');
      panels.forEach(panel => panel.classList.remove('active'));
  
      const targetPanel = document.getElementById(panelId);
      if (targetPanel) {
          targetPanel.classList.add('active');
      }
     
      if (evt && evt.currentTarget) {
          evt.currentTarget.classList.add('active');
      } else if (window.event && window.event.currentTarget) {
          window.event.currentTarget.classList.add('active');
      }
  }
  
  function togglePasswordVisibility(inputId) {
      const passwordField = document.getElementById(inputId);
      if (!passwordField) return;
  
      if (passwordField.type === "password") {
          passwordField.type = "text";
      } else {
          passwordField.type = "password";
      }
  }
  