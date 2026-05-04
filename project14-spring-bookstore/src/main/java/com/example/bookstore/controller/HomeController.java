package com.example.bookstore.controller;

import com.example.bookstore.model.Book;
import com.example.bookstore.repository.BookRepository;
import com.example.bookstore.service.UserService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@Controller
public class HomeController {

    @Autowired
    private BookRepository bookRepository;

    @Autowired
    private UserService userService;

    // Home page
    @GetMapping({"/", "/home"})
    public String homePage() {
        return "home";
    }

    // Login page
    @GetMapping("/login")
    public String loginPage(@RequestParam(required = false) String error,
                            @RequestParam(required = false) String logout,
                            Model model) {
        if (error != null)  model.addAttribute("error",   "Invalid username or password.");
        if (logout != null) model.addAttribute("message", "You have been logged out.");
        return "login";
    }

    // Registration page
    @GetMapping("/register")
    public String registerPage() {
        return "register";
    }

    // Handle registration
    @PostMapping("/register")
    public String handleRegister(@RequestParam String username,
                                 @RequestParam String email,
                                 @RequestParam String password,
                                 Model model) {
        try {
            userService.register(username, email, password);
            model.addAttribute("message", "Registration successful! Please login.");
            return "login";
        } catch (Exception e) {
            model.addAttribute("error", e.getMessage());
            return "register";
        }
    }

    // Catalog page
    @GetMapping("/catalog")
    public String catalogPage(@RequestParam(required = false) String search,
                              @RequestParam(required = false) String category,
                              Model model) {
        List<Book> books;
        if (search != null && !search.isEmpty()) {
            books = bookRepository.findByTitleContainingIgnoreCase(search);
        } else if (category != null && !category.isEmpty()) {
            books = bookRepository.findByCategory(category);
        } else {
            books = bookRepository.findAll();
        }
        model.addAttribute("books", books);
        model.addAttribute("search", search);
        model.addAttribute("category", category);
        return "catalog";
    }
}
