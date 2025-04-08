const API_BASE_URL = "https://www.themealdb.com/api/json/v1/1/";
const MAX_SUGGESTIONS = 10;

document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("search-input");
    const suggestionsList = document.getElementById("suggestions-list");
    const searchResults = document.getElementById("search-results");


    function debounce(func, delay) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }


    async function fetchSuggestions(query) {
        try {
            const response = await fetch(`${API_BASE_URL}list.php?i=list`);
            const data = await response.json();
            return data.meals
                .filter((ingredient) =>
                    ingredient.strIngredient
                        .toLowerCase()
                        .includes(query.toLowerCase())
                )
                .slice(0, MAX_SUGGESTIONS);
        } catch (error) {
            console.error("Error fetching suggestions:", error);
            return [];
        }
    }


    function displaySuggestions(suggestions) {
        suggestionsList.innerHTML = suggestions
            .map(
                (ingredient) => `
                <li class="suggestion-item p-2 hover:bg-gray-100 cursor-pointer" data-ingredient="${ingredient.strIngredient}">
                    ${ingredient.strIngredient}
                </li>
            `
            )
            .join("");
        suggestionsList.classList.remove("hidden");
    }

    searchInput.addEventListener(
        "input",
        debounce(async (e) => {
            const query = e.target.value.trim();
            if (query.length > 0) {
                const suggestions = await fetchSuggestions(query);
                displaySuggestions(suggestions);
            } else {
                suggestionsList.innerHTML = "";
                suggestionsList.classList.add("hidden");
            }
        }, 200)
    );

    suggestionsList.addEventListener("click", (e) => {
        if (e.target.classList.contains("suggestion-item")) {
            const ingredient = e.target.dataset.ingredient;
            searchInput.value = ingredient;
            suggestionsList.innerHTML = "";
            suggestionsList.classList.add("hidden");
            performSearch([ingredient]);
        }
    });


    async function performSearch(ingredients) {
        try {
            const mealPromises = ingredients.map(async (ingredient) => {
                const response = await fetch(
                    `${API_BASE_URL}filter.php?i=${ingredient}`
                );
                const data = await response.json();
                return data.meals || [];
            });


           
            const mealResults = await Promise.all(mealPromises);
            
            const commonMeals = mealResults.reduce((acc, meals) => {
                return acc.filter((meal) =>
                    meals.some((m) => m.idMeal === meal.idMeal)
                );
            }, mealResults[0] || []);

            if (!commonMeals || commonMeals.length === 0) {
                displayError("No recipes found containing all the specified ingredients.");
            } else {
                displayResults(commonMeals);
            }
        } catch (error) {
            console.error("Search error:", error);
            displayError("An error occurred while searching. Please try again.");
        }
    }


    function displayResults(meals) {
        searchResults.innerHTML = meals
            .map(
                (meal) => `
            <div class="recipe-card border p-4 w-64 mb-4 shadow-2xl rounded-lg overflow-hidden bg-white" data-mealid="${meal.idMeal}">
                <div class="w-full h-40 overflow-hidden">
                    <img src="${meal.strMealThumb}" alt="${meal.strMeal}" class="w-full h-full object-cover rounded-t-lg">
                </div>
                <div class="p-3">
                    <p class="text-center font-bold text-lg">${meal.strMeal}</p>
                    <div class="text-center mt-2 text-sm text-gray-600" id="rating-info-${meal.idMeal}">Loading rating...</div>
                    <div class="flex flex-col mt-2">
                        <input type="number" min="1" max="5" step="0.1" placeholder="Rate (1-5)" id="rating-${meal.idMeal}" class="border p-2 mb-2 rounded-md w-full" />
    
                        <button class="border p-2 bg-yellow-400 hover:bg-yellow-300 rounded-md w-full" onclick="rateMeal('${meal.idMeal}')">
                            <i class="fas fa-star"></i> Submit Rating
                        </button>
                    </div>
                    <div class="flex justify-between mt-2">
                        <button class="border p-2 bg-red-400 hover:bg-red-300 rounded-md flex-1 mr-1" onclick="saveMeal('${meal.idMeal}')">
                            <i class="fas fa-heart"></i> Save
                        </button>
                        <button class="border p-2 bg-blue-400 hover:bg-blue-300 rounded-md flex-1 ml-1" onclick="bookmarkMeal('${meal.idMeal}')">
                            <i class="fas fa-calendar"></i> Bookmark
                        </button>
                    </div>
                    <button class="border p-2 w-full mt-2 bg-gray-200 hover:bg-gray-100 rounded-md" onclick="showRecipeDetails('${meal.idMeal}')">
                        View Recipe
                    </button>
                </div>
            </div>`
            )
            .join('');
    
        meals.forEach((meal) => {
            loadRatings(meal.idMeal); // Load ratings when meals are displayed
        });
    }

    window.rateMeal = async function (mealId) {
        const ratingInput = document.getElementById(`rating-${mealId}`);
        const rating = parseFloat(ratingInput.value);

        if (!rating || rating < 1 || rating > 5) {
            alert("Please enter a valid rating, a number (1-5), 1 being very bad and 5 very good.");
            return;
        }

        let response = await fetch("rateMeal.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ mealId, rating }),
        });

        let result = await response.json();

        if (!result.success && result.message.includes("Overwrite")) {
            if (confirm(result.message)) {
                response = await fetch("rateMeal.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ mealId, rating, overwrite: true }),
                });

                result = await response.json();
            } else {
                return;
            }
        }

        if (result.success) {
            alert(result.message);
            updateRatingDisplay(mealId, result.averageRating, result.userCount);
        } else {
            alert(result.message);
        }
    };

    function generateStars(rating) {
        const fullStars = Math.floor(rating);
        const halfStar = rating % 1 >= 0.5 ? 1 : 0;
        const emptyStars = 5 - fullStars - halfStar;
    
        return "★".repeat(fullStars) + (halfStar ? "✩" : "") + "☆".repeat(emptyStars);
    }
    

    async function loadRatings(mealId) {
        const response = await fetch("rateMeal.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ mealId }),
        });
    
        const result = await response.json();
    
        updateRatingDisplay(mealId, result.averageRating, result.userCount);
    }
    
    function updateRatingDisplay(mealId, averageRating = 0, userCount = 0) {
        const ratingInfo = document.getElementById(`rating-info-${mealId}`);
        ratingInfo.innerHTML = `
            <span class="text-yellow-500 text-lg">${generateStars(averageRating)}</span>
            <span class="ml-2">${averageRating.toFixed(1)} - (${userCount} users)</span>
        `;


    }
    
    function displayError(message) {
        searchResults.innerHTML = `<p class="text-red-500">${message}</p>`;
    }

    const searchForm = document.getElementById("search-form");
    searchForm.addEventListener("submit", (e) => {
        suggestionsList.classList.add("hidden"); // Hide suggestions when search is submitted

        e.preventDefault();
        const ingredients = searchInput.value
            .split(",")
            .map((ing) => ing.trim())
            .filter((ing) => ing.length > 0);
        if (ingredients.length > 0) {
            performSearch(ingredients);
        } else {
            displayError("Please enter at least one ingredient to search.");
        }
    });
});
