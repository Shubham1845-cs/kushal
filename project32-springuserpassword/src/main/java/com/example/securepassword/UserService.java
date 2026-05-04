package com.example.securepassword;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.userdetails.*;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;
import java.util.*;

@Service
public class UserService implements UserDetailsService {
    
    @Autowired private UserRepository repo;
    @Autowired private PasswordEncoder encoder;
    
    // Register user with encrypted password
    public void register(String username, String password) {
        User user = new User();
        user.setUsername(username);
        user.setPassword(encoder.encode(password));  // Encrypt password
        repo.save(user);
    }
    
    // For Spring Security authentication
    @Override
    public UserDetails loadUserByUsername(String username) {
        User user = repo.findByUsername(username)
            .orElseThrow(() -> new UsernameNotFoundException("User not found"));
        
        return org.springframework.security.core.userdetails.User
            .withUsername(user.getUsername())
            .password(user.getPassword())
            .roles("USER")
            .build();
    }
    
    public List<User> getAllUsers() {
        return repo.findAll();
    }
}
