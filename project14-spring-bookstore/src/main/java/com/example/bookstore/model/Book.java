package com.example.bookstore.model;

import jakarta.persistence.*;

@Entity
@Table(name = "books")
public class Book {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Integer id;

    @Column(nullable = false)
    private String title;

    @Column(nullable = false)
    private String author;

    @Column(nullable = false)
    private Double price;

    @Column(length = 500)
    private String description;

    @Column(nullable = false)
    private Integer quantity;

    @Column(name = "category")
    private String category;

    public Book() {}

    public Book(String title, String author, Double price, String description, Integer quantity, String category) {
        this.title       = title;
        this.author      = author;
        this.price       = price;
        this.description = description;
        this.quantity    = quantity;
        this.category    = category;
    }

    // Getters and Setters
    public Integer getId()                    { return id; }
    public void setId(Integer id)             { this.id = id; }
    public String getTitle()                  { return title; }
    public void setTitle(String title)        { this.title = title; }
    public String getAuthor()                 { return author; }
    public void setAuthor(String author)      { this.author = author; }
    public Double getPrice()                  { return price; }
    public void setPrice(Double price)        { this.price = price; }
    public String getDescription()            { return description; }
    public void setDescription(String d)      { this.description = d; }
    public Integer getQuantity()              { return quantity; }
    public void setQuantity(Integer quantity) { this.quantity = quantity; }
    public String getCategory()               { return category; }
    public void setCategory(String category)  { this.category = category; }
}
