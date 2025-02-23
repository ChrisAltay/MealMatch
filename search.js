const API_BASE_URL = 'https://www.themealdb.com/api/json/v1/1/';
const MAX_SUGGESTIONS = 10;

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-input');
    const suggestionsList = document.getElementById('suggestions-list');
    const searchResults = document.getElementById('search-results');

    // Debounce function to limit API calls
    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    // Fetch ingredient suggestions
    async function fetchSuggestions(query) {
        try {
            const response = await fetch(`${API_BASE_URL}list.php?i=list`);
            const data = await response.json();
            return data.meals
                .filter(ingredient => 
                    ingredient.strIngredient.toLowerCase().includes(query.toLowerCase())
                )
                .slice(0, MAX_SUGGESTIONS);
        } catch (error) {
            console.error('Error fetching suggestions:', error);
            return [];
        }
    }

    // Display suggestions
    function displaySuggestions(suggestions) {
        suggestionsList.innerHTML = suggestions
            .map(ingredient => `
                <li class="suggestion-item p-2 hover:bg-gray-100 cursor-pointer" data-ingredient="${ingredient.strIngredient}">
                    ${ingredient.strIngredient}
                </li>
            `)
            .join('');
        suggestionsList.classList.remove('hidden');
    }

    // Handle input for autocomplete
    searchInput.addEventListener('input', debounce(async (e) => {
        const query = e.target.value.trim();
        if (query.length > 0) {
            const suggestions = await fetchSuggestions(query);
            displaySuggestions(suggestions);
        } else {
            suggestionsList.innerHTML = '';
            suggestionsList.classList.add('hidden');
        }
    }, 200));

    // Handle suggestion click
    suggestionsList.addEventListener('click', (e) => {
        if (e.target.classList.contains('suggestion-item')) {
            const ingredient = e.target.dataset.ingredient;
            searchInput.value = ingredient;
            suggestionsList.innerHTML = '';
            suggestionsList.classList.add('hidden');
            performSearch([ingredient]);
        }
    });

    // Perform search with multiple ingredients
    async function performSearch(ingredients) {
        try {
            // Fetch meals for each ingredient
            const mealPromises = ingredients.map(async ingredient => {
                const response = await fetch(`${API_BASE_URL}filter.php?i=${ingredient}`);
                const data = await response.json();
                return data.meals || [];
            });

            // Wait for all promises to resolve
            const mealResults = await Promise.all(mealPromises);

            // Find meals that contain all ingredients
            const commonMeals = mealResults.reduce((acc, meals) => {
                return acc.filter(meal => meals.some(m => m.idMeal === meal.idMeal));
            }, mealResults[0] || []);

            if (!commonMeals || commonMeals.length === 0) {
                displayError('No recipes found containing all the specified ingredients.');
            } else {
                displayResults(commonMeals);
            }
        } catch (error) {
            console.error('Search error:', error);
            displayError('An error occurred while searching. Please try again.');
        }
    }

    // Display search results
    function displayResults(meals) {
        searchResults.innerHTML = meals
            .map(meal => `
                <div class="recipe-card border p-4 w-64 mb-4">
                    <img src="${meal.strMealThumb}" alt="${meal.strMeal}" class="w-full h-auto mb-2">
                    <p class="text-center">${meal.strMeal}</p>
                    <button class="border p-2 w-full mt-2" onclick="showRecipeDetails('${meal.idMeal}')">
                        View Recipe
                    </button>
                </div>
            `)
            .join('');
    }

    // Show recipe details
    window.showRecipeDetails = async function(mealId) {
        try {
            const response = await fetch(`${API_BASE_URL}lookup.php?i=${mealId}`);
            const data = await response.json();
            const meal = data.meals[0];
            displayRecipeDetails(meal);
        } catch (error) {
            console.error('Error fetching recipe details:', error);
            displayError('Failed to load recipe details. Please try again.');
        }
    };

    // Display recipe details in a modal
    function displayRecipeDetails(meal) {
        const ingredients = [];
        for (let i = 1; i <= 20; i++) {
            if (meal[`strIngredient${i}`]) {
                ingredients.push(`${meal[`strIngredient${i}`]} - ${meal[`strMeasure${i}`]}`);
            }
        }

        const modalContent = `
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
                <div class="bg-white p-6 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <h2 class="text-2xl font-bold mb-4">${meal.strMeal}</h2>
                    <img src="${meal.strMealThumb}" alt="${meal.strMeal}" class="w-full h-64 object-cover mb-4">
                    <h3 class="text-xl font-semibold mb-2">Ingredients:</h3>
                    <ul class="mb-4">
                        ${ingredients.map(ing => `<li>${ing}</li>`).join('')}
                    </ul>
                    <h3 class="text-xl font-semibold mb-2">Instructions:</h3>
                    <p class="mb-4">${meal.strInstructions}</p>
                    <button onclick="closeModal()" class="bg-red-500 text-white px-4 py-2 rounded">Close</button>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalContent);
    }

    // Close modal
    window.closeModal = function() {
        const modal = document.querySelector('.fixed.inset-0');
        if (modal) modal.remove();
    };

    // Display error message
    function displayError(message) {
        searchResults.innerHTML = `<p class="text-red-500">${message}</p>`;
    }

    // Initialize search form
    const searchForm = document.getElementById('search-form');
    searchForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const ingredients = searchInput.value
            .split(',')
            .map(ing => ing.trim())
            .filter(ing => ing.length > 0);
        if (ingredients.length > 0) {
            performSearch(ingredients);
        } else {
            displayError('Please enter at least one ingredient to search.');
        }
    });
});