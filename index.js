// index.js

// Get the search input and button elements
const searchInput = document.querySelector('.search-bar input');
const searchButton = document.querySelector('.search-button');

// Add an event listener to the search button
searchButton.addEventListener('click', () => {
    const searchTerm = searchInput.value;
    // You can perform a search action here (e.g., fetch data from an API)
    console.log(`Searching for: ${searchTerm}`);
    // Replace this console.log with your actual search logic
});
