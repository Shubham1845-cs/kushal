package com.example.inventory.model;

import org.springframework.data.annotation.Id;
import org.springframework.data.mongodb.core.mapping.Document;

@Document(collection = "products")
public class Product {

    @Id
    private String id;

    private String name;
    private String category;
    private Double price;
    private Integer quantity;
    private String description;

    // Constructors
    public Product() {}

    public Product(String name, String category, Double price, Integer quantity, String description) {
        this.name        = name;
        this.category    = category;
        this.price       = price;
        this.quantity    = quantity;
        this.description = description;
    }

    // Getters and Setters
    public String getId()                    { return id; }
    public void setId(String id)             { this.id = id; }

    public String getName()                  { return name; }
    public void setName(String name)         { this.name = name; }

    public String getCategory()              { return category; }
    public void setCategory(String c)        { this.category = c; }

    public Double getPrice()                 { return price; }
    public void setPrice(Double price)       { this.price = price; }

    public Integer getQuantity()             { return quantity; }
    public void setQuantity(Integer q)       { this.quantity = q; }

    public String getDescription()           { return description; }
    public void setDescription(String d)     { this.description = d; }
}
