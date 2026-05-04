package com.example.inventory.controller;

import com.example.inventory.model.Product;
import com.example.inventory.repository.ProductRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Optional;

@RestController
@RequestMapping("/api/products")
@CrossOrigin(origins = "*")
public class ProductController {

    @Autowired
    private ProductRepository productRepository;

    // GET all products
    // GET /api/products
    @GetMapping
    public ResponseEntity<Map<String, Object>> getAllProducts() {
        List<Product> products = productRepository.findAll();
        Map<String, Object> response = new HashMap<>();
        response.put("success", true);
        response.put("count", products.size());
        response.put("data", products);
        return ResponseEntity.ok(response);
    }

    // GET product by ID
    // GET /api/products/{id}
    @GetMapping("/{id}")
    public ResponseEntity<Map<String, Object>> getProductById(@PathVariable String id) {
        Map<String, Object> response = new HashMap<>();
        Optional<Product> product = productRepository.findById(id);
        if (product.isPresent()) {
            response.put("success", true);
            response.put("data", product.get());
            return ResponseEntity.ok(response);
        }
        response.put("success", false);
        response.put("message", "Product not found with id: " + id);
        return ResponseEntity.status(HttpStatus.NOT_FOUND).body(response);
    }

    // GET products by category
    // GET /api/products/category/{category}
    @GetMapping("/category/{category}")
    public ResponseEntity<Map<String, Object>> getByCategory(@PathVariable String category) {
        List<Product> products = productRepository.findByCategory(category);
        Map<String, Object> response = new HashMap<>();
        response.put("success", true);
        response.put("count", products.size());
        response.put("data", products);
        return ResponseEntity.ok(response);
    }

    // GET products by name search
    // GET /api/products/search/{name}
    @GetMapping("/search/{name}")
    public ResponseEntity<Map<String, Object>> searchByName(@PathVariable String name) {
        List<Product> products = productRepository.findByNameContainingIgnoreCase(name);
        Map<String, Object> response = new HashMap<>();
        response.put("success", true);
        response.put("count", products.size());
        response.put("data", products);
        return ResponseEntity.ok(response);
    }

    // POST create new product
    // POST /api/products
    // Body: { name, category, price, quantity, description }
    @PostMapping
    public ResponseEntity<Map<String, Object>> createProduct(@RequestBody Product product) {
        Map<String, Object> response = new HashMap<>();
        if (product.getName() == null || product.getCategory() == null ||
            product.getPrice() == null || product.getQuantity() == null) {
            response.put("success", false);
            response.put("message", "name, category, price and quantity are required");
            return ResponseEntity.badRequest().body(response);
        }
        Product saved = productRepository.save(product);
        response.put("success", true);
        response.put("message", "Product created successfully");
        response.put("data", saved);
        return ResponseEntity.status(HttpStatus.CREATED).body(response);
    }

    // PUT update product
    // PUT /api/products/{id}
    // Body: { name, category, price, quantity, description }
    @PutMapping("/{id}")
    public ResponseEntity<Map<String, Object>> updateProduct(@PathVariable String id,
                                                              @RequestBody Product updated) {
        Map<String, Object> response = new HashMap<>();
        Optional<Product> existing = productRepository.findById(id);
        if (existing.isEmpty()) {
            response.put("success", false);
            response.put("message", "Product not found with id: " + id);
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body(response);
        }
        Product product = existing.get();
        if (updated.getName()        != null) product.setName(updated.getName());
        if (updated.getCategory()    != null) product.setCategory(updated.getCategory());
        if (updated.getPrice()       != null) product.setPrice(updated.getPrice());
        if (updated.getQuantity()    != null) product.setQuantity(updated.getQuantity());
        if (updated.getDescription() != null) product.setDescription(updated.getDescription());

        Product saved = productRepository.save(product);
        response.put("success", true);
        response.put("message", "Product updated successfully");
        response.put("data", saved);
        return ResponseEntity.ok(response);
    }

    // DELETE product
    // DELETE /api/products/{id}
    @DeleteMapping("/{id}")
    public ResponseEntity<Map<String, Object>> deleteProduct(@PathVariable String id) {
        Map<String, Object> response = new HashMap<>();
        if (!productRepository.existsById(id)) {
            response.put("success", false);
            response.put("message", "Product not found with id: " + id);
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body(response);
        }
        productRepository.deleteById(id);
        response.put("success", true);
        response.put("message", "Product deleted successfully");
        return ResponseEntity.ok(response);
    }

    // Root info
    @GetMapping("/info")
    public ResponseEntity<Map<String, Object>> info() {
        Map<String, Object> response = new HashMap<>();
        response.put("app", "Product Inventory Management System");
        response.put("database", "MongoDB");
        response.put("auth", "Basic Authentication (admin/admin123)");
        response.put("endpoints", Map.of(
            "GET    /api/products",              "Get all products",
            "GET    /api/products/{id}",         "Get product by ID",
            "GET    /api/products/category/{c}", "Get by category",
            "GET    /api/products/search/{name}","Search by name",
            "POST   /api/products",              "Create product",
            "PUT    /api/products/{id}",         "Update product",
            "DELETE /api/products/{id}",         "Delete product"
        ));
        return ResponseEntity.ok(response);
    }
}
