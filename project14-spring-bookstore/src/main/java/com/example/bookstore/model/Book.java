package com.example.bookstore.model;

import jakarta.persistence.*;
import lombok.Data;

@Entity
@Table(name = "books")
@Data
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
}
