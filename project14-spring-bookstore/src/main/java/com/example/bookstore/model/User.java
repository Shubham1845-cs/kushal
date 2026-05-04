package com.example.bookstore.model;

import jakarta.persistence.*;

@Entity
@Table(name = "users")
public class User {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Integer id;

    @Column(nullable = false, unique = true)
    private String username;

    @Column(nullable = false, unique = true)
    private String email;

    @Column(nullable = false)
    private String password;

    @Column(name = "role")
    private String role = "USER";

    public User() {}

    // Getters and Setters
    public Integer getId()               { return id; }
    public void setId(Integer id)        { this.id = id; }
    public String getUsername()          { return username; }
    public void setUsername(String u)    { this.username = u; }
    public String getEmail()             { return email; }
    public void setEmail(String e)       { this.email = e; }
    public String getPassword()          { return password; }
    public void setPassword(String p)    { this.password = p; }
    public String getRole()              { return role; }
    public void setRole(String role)     { this.role = role; }
}
