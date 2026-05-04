package com.example.bookstore.config;

import com.example.bookstore.model.Book;
import com.example.bookstore.repository.BookRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.CommandLineRunner;
import org.springframework.stereotype.Component;

@Component
public class DataLoader implements CommandLineRunner {

    @Autowired
    private BookRepository bookRepository;

    @Override
    public void run(String... args) {
        if (bookRepository.count() == 0) {
            bookRepository.save(new Book("Clean Code",              "Robert C. Martin", 599.0,  "A handbook of agile software craftsmanship.",     10, "Technology"));
            bookRepository.save(new Book("The Great Gatsby",        "F. Scott Fitzgerald", 299.0, "A story of the American dream.",                 8,  "Fiction"));
            bookRepository.save(new Book("To Kill a Mockingbird",   "Harper Lee",        349.0,  "A novel about racial injustice.",                 6,  "Fiction"));
            bookRepository.save(new Book("1984",                    "George Orwell",     279.0,  "A dystopian social science fiction novel.",       12, "Fiction"));
            bookRepository.save(new Book("The Alchemist",           "Paulo Coelho",      249.0,  "A philosophical novel about following dreams.",   15, "Fiction"));
            bookRepository.save(new Book("Introduction to Java",    "Herbert Schildt",   699.0,  "Complete reference for Java programming.",        5,  "Technology"));
            bookRepository.save(new Book("A Brief History of Time", "Stephen Hawking",   399.0,  "From the Big Bang to Black Holes.",               7,  "Science"));
            bookRepository.save(new Book("Sapiens",                 "Yuval Noah Harari", 449.0,  "A brief history of humankind.",                   9,  "History"));
            System.out.println("Sample books loaded.");
        }
    }
}
