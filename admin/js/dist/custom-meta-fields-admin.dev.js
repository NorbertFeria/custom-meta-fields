"use strict";

jQuery(document).ready(function ($) {
  // Function to activate the tab
  function activateTab(tab) {
    // Remove 'nav-tab-active' class from all tabs
    $('.nav-tab-wrapper .nav-tab').removeClass('nav-tab-active'); // Add 'nav-tab-active' class to the tab corresponding to the URL parameter

    $('.nav-tab-wrapper .nav-tab[href="#' + tab + '"]').addClass('nav-tab-active'); // Hide all tab content

    $('.tab-content').hide(); // Show the corresponding tab content

    $('#' + tab).show();
  } // Get the 'tab' parameter from the URL


  var urlParams = new URLSearchParams(window.location.search);
  var tab = urlParams.get('tab'); // If a 'tab' parameter exists in the URL, activate that tab, else activate the default tab

  if (tab) {
    activateTab(tab);
  } else {
    activateTab('existing-meta-boxes');
  } // Bind click event to tabs


  $('.nav-tab-wrapper .nav-tab').on('click', function (e) {
    e.preventDefault(); // Prevent default anchor behavior
    // Get the href of the clicked tab, remove the '#' and set it as the active tab

    var clickedTab = $(this).attr('href').substring(1);
    activateTab(clickedTab); // Update the URL with the new tab

    var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?page=custom-meta-fields&tab=' + clickedTab;
    history.replaceState(null, null, newUrl);
  });

  function toggleFieldChoices() {
    var selectedValue = $('#field_type').val();

    if (selectedValue === 'Dropdown' || selectedValue === 'Radio' || selectedValue === 'Checkbox') {
      $('tr.field_choices').show();
    } else {
      $('tr.field_choices').hide();
    }
  } // Initial check on page load


  toggleFieldChoices(); // Add event listener to the field type select element

  $('#field_type').on('change', toggleFieldChoices);
});