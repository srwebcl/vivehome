App\Models\Property::create([
    'user_id' => $asesor ? $asesor->id : null,
    'category_id' => $categoria ? $categoria->id : null,
    'title' => 'Propiedad Increíble de Prueba (Admin)',
    'description' => 'Esta es la descripción de la propiedad creada desde Tinker para probar la vista de detalles del administrador. Cuenta con amplios espacios y excelente ubicación.',
    'operation_type' => 'Venta', // Asegúrate que tu tabla 'properties' tiene esta columna
    'type_property' => 'Departamento', // Asegúrate que tu tabla 'properties' tiene esta columna
    'status' => 'Disponible',      // Asegúrate que tu tabla 'properties' tiene esta columna
    'price' => 120000000,           // Asegúrate que tu tabla 'properties' tiene esta columna
    'price_currency' => 'CLP',      // Asegúrate que tu tabla 'properties' tiene esta columna
    'address' => 'Calle Ficticia 123', // Asegúrate que tu tabla 'properties' tiene esta columna
    'commune' => 'Comuna Ejemplo',  // Asegúrate que tu tabla 'properties' tiene esta columna
    'city' => 'Ciudad Ejemplo',     // Asegúrate que tu tabla 'properties' tiene esta columna
    'bedrooms' => 3,                // Asegúrate que tu tabla 'properties' tiene esta columna
    'bathrooms' => 2,               // Asegúrate que tu tabla 'properties' tiene esta columna
    'surface_total' => 100,         // Asegúrate que tu tabla 'properties' tiene esta columna
    'surface_built' => 85           // Asegúrate que tu tabla 'properties' tiene esta columna
]);