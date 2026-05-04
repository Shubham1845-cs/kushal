package com.example.order.model;

import jakarta.persistence.*;
import java.time.LocalDateTime;

@Entity
@Table(name = "orders")
public class Order {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(nullable = false)
    private String customerName;

    @Column(nullable = false)
    private String customerEmail;

    @Column(nullable = false)
    private String productName;

    @Column(nullable = false)
    private Integer quantity;

    @Column(nullable = false)
    private Double totalPrice;

    @Column(nullable = false)
    private String status; // PENDING, CONFIRMED, SHIPPED, DELIVERED, CANCELLED

    @Column(nullable = false)
    private String shippingAddress;

    @Column(updatable = false)
    private LocalDateTime createdAt;

    private LocalDateTime updatedAt;

    @PrePersist
    protected void onCreate() {
        createdAt = LocalDateTime.now();
        updatedAt = LocalDateTime.now();
        if (status == null) status = "PENDING";
    }

    @PreUpdate
    protected void onUpdate() {
        updatedAt = LocalDateTime.now();
    }

    // Constructors
    public Order() {}

    public Order(String customerName, String customerEmail, String productName,
                 Integer quantity, Double totalPrice, String shippingAddress) {
        this.customerName    = customerName;
        this.customerEmail   = customerEmail;
        this.productName     = productName;
        this.quantity        = quantity;
        this.totalPrice      = totalPrice;
        this.shippingAddress = shippingAddress;
        this.status          = "PENDING";
    }

    // Getters and Setters
    public Long getId()                          { return id; }
    public void setId(Long id)                   { this.id = id; }

    public String getCustomerName()              { return customerName; }
    public void setCustomerName(String n)        { this.customerName = n; }

    public String getCustomerEmail()             { return customerEmail; }
    public void setCustomerEmail(String e)       { this.customerEmail = e; }

    public String getProductName()               { return productName; }
    public void setProductName(String p)         { this.productName = p; }

    public Integer getQuantity()                 { return quantity; }
    public void setQuantity(Integer q)           { this.quantity = q; }

    public Double getTotalPrice()                { return totalPrice; }
    public void setTotalPrice(Double t)          { this.totalPrice = t; }

    public String getStatus()                    { return status; }
    public void setStatus(String s)              { this.status = s; }

    public String getShippingAddress()           { return shippingAddress; }
    public void setShippingAddress(String a)     { this.shippingAddress = a; }

    public LocalDateTime getCreatedAt()          { return createdAt; }
    public LocalDateTime getUpdatedAt()          { return updatedAt; }
}
