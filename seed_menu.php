<?php
require_once "config/database.php";

$categories = [
    'Burgers' => 'Juicy, flame-grilled burgers made with 100% Angus beef.',
    'Pizzas' => 'Hand-tossed artisan pizzas baked to perfection.',
    'Salads' => 'Fresh, crisp greens with homemade dressings.',
    'Drinks' => 'Refreshing cold and hot beverages.',
    'Desserts' => 'Sweet treats to end your meal right.'
];

$cat_ids = [];
foreach ($categories as $name => $desc) {
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ?");
    $stmt->execute([$name]);
    $cat = $stmt->fetch();
    if ($cat) {
        $cat_ids[$name] = $cat['id'];
    } else {
        $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        $stmt->execute([$name, $desc]);
        $cat_ids[$name] = $pdo->lastInsertId();
    }
}

$foods = [
    ['Burgers', 'Classic Cheeseburger', 'Angus beef patty, cheddar cheese, lettuce, tomato, house sauce.', 8.99],
    ['Burgers', 'Bacon Double Smash', 'Two smashed patties, crispy bacon, American cheese, caramelized onions.', 12.99],
    ['Burgers', 'Mushroom Swiss Burger', 'Swiss cheese, sautéed mushrooms, garlic aioli on a brioche bun.', 10.99],
    ['Burgers', 'Spicy Jalapeno Burger', 'Pepper jack cheese, fresh jalapenos, spicy mayo.', 11.49],
    
    ['Pizzas', 'Margherita Pizza', 'San Marzano tomato sauce, fresh mozzarella, basil, olive oil.', 14.99],
    ['Pizzas', 'Pepperoni Feast', 'Double pepperoni, mozzarella, rich tomato sauce.', 16.99],
    ['Pizzas', 'BBQ Chicken Pizza', 'Grilled chicken, red onions, cilantro, BBQ sauce base.', 17.99],
    ['Pizzas', 'Veggie Supreme', 'Bell peppers, mushrooms, onions, black olives, spinach.', 15.99],
    
    ['Salads', 'Caesar Salad', 'Crisp romaine, parmesan cheese, croutons, creamy Caesar dressing.', 7.99],
    ['Salads', 'Greek Salad', 'Cucumbers, tomatoes, feta cheese, kalamata olives, red onions.', 8.99],
    ['Salads', 'Cobb Salad', 'Mixed greens, grilled chicken, bacon, hard-boiled egg, avocado, blue cheese.', 11.99],
    ['Salads', 'Quinoa Power Bowl', 'Quinoa, roasted sweet potatoes, kale, chickpeas, tahini dressing.', 10.49],
    
    ['Drinks', 'Classic Lemonade', 'Freshly squeezed lemons, pure cane sugar, over ice.', 3.99],
    ['Drinks', 'Iced Caramel Macchiato', 'Espresso, milk, vanilla syrup, caramel drizzle.', 4.99],
    ['Drinks', 'Strawberry Smoothie', 'Fresh strawberries, yogurt, honey blended to perfection.', 5.99],
    ['Drinks', 'Coca-Cola', 'Chilled 16oz fountain drink.', 2.49],
    
    ['Desserts', 'New York Cheesecake', 'Classic creamy cheesecake with a graham cracker crust.', 6.99],
    ['Desserts', 'Warm Chocolate Brownie', 'Fudge brownie served warm with vanilla bean ice cream.', 7.49],
    ['Desserts', 'Tiramisu', 'Coffee-soaked ladyfingers, mascarpone cream, cocoa powder.', 8.49],
    ['Desserts', 'Apple Pie', 'Traditional apple pie with a flaky crust and cinnamon glaze.', 5.99]
];

$count = 0;
foreach ($foods as $food) {
    $cat_id = $cat_ids[$food[0]];
    
    $stmt = $pdo->prepare("SELECT id FROM foods WHERE name = ?");
    $stmt->execute([$food[1]]);
    if (!$stmt->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO foods (category_id, name, description, price, is_available) VALUES (?, ?, ?, ?, 1)");
        $stmt->execute([$cat_id, $food[1], $food[2], $food[3]]);
        $count++;
    }
}
echo "Successfully added $count new food items!\n";
?>
