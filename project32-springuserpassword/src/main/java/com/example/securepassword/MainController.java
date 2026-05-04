package com.example.securepassword;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.Authentication;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

@Controller
public class MainController {
    
    @Autowired private UserService service;
    
    @GetMapping("/")
    public String home() {
        return "index";
    }
    
    @PostMapping("/register")
    public String register(@RequestParam String username, 
                          @RequestParam String password, Model model) {
        try {
            service.register(username, password);
            model.addAttribute("message", "Registration successful! Please login.");
        } catch (Exception e) {
            model.addAttribute("error", "Username already exists!");
        }
        return "index";
    }
    
    @GetMapping("/dashboard")
    public String dashboard(Authentication auth, Model model) {
        model.addAttribute("username", auth.getName());
        model.addAttribute("users", service.getAllUsers());
        return "dashboard";
    }
}
