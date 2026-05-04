package com.example.inventory.config;

import com.example.inventory.model.Product;
import com.example.inventory.repository.ProductRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.CommandLineRunner;
import org.springframework.stereotype.Component;

@Component
public class DataLoader implements CommandLineRunner {

    @Autowired
    private ProductRepository productRepository;

    @Override
    public void run(String... args) {
        if (productRepository.count() == 0) {
            productRepository.save(new Product("Laptop",        "Electronics", 75000.0, 10, "High performance laptop"));
            productRepository.save(new Product("Wireless Mouse","Electronics",  1200.0, 50, "Ergonomic wireless mouse"));
            productRepository.save(new Product("Office Chair",  "Furniture",   8500.0,  5, "Comfortable office chair"));
            productRepository.save(new Product("Notebook",      "Stationery",   150.0, 100, "A4 ruled notebook"));
            productRepository.save(new Product("Headphones",    "Electronics",  5000.0, 20, "Noise cancelling headphones"));
            System.out.println("Sample products loaded into MongoDB.");
        }
    }
}
