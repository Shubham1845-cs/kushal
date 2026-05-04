package com.example.order.controller;

import com.example.order.model.Order;
import com.example.order.service.OrderService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

@RestController
@RequestMapping("/api/orders")
@CrossOrigin(origins = "*")
public class OrderController {

    @Autowired
    private OrderService orderService;

    // GET all orders
    // GET /api/orders
    @GetMapping
    public ResponseEntity<Map<String, Object>> getAllOrders() {
        List<Order> orders = orderService.getAllOrders();
        Map<String, Object> response = new HashMap<>();
        response.put("success", true);
        response.put("count", orders.size());
        response.put("data", orders);
        return ResponseEntity.ok(response);
    }

    // GET order by ID
    // GET /api/orders/{id}
    @GetMapping("/{id}")
    public ResponseEntity<Map<String, Object>> getOrderById(@PathVariable Long id) {
        Map<String, Object> response = new HashMap<>();
        return orderService.getOrderById(id)
            .map(order -> {
                response.put("success", true);
                response.put("data", order);
                return ResponseEntity.ok(response);
            })
            .orElseGet(() -> {
                response.put("success", false);
                response.put("message", "Order not found with id: " + id);
                return ResponseEntity.status(HttpStatus.NOT_FOUND).body(response);
            });
    }

    // GET orders by status
    // GET /api/orders/status/{status}
    @GetMapping("/status/{status}")
    public ResponseEntity<Map<String, Object>> getOrdersByStatus(@PathVariable String status) {
        List<Order> orders = orderService.getOrdersByStatus(status);
        Map<String, Object> response = new HashMap<>();
        response.put("success", true);
        response.put("count", orders.size());
        response.put("data", orders);
        return ResponseEntity.ok(response);
    }

    // GET orders by customer email
    // GET /api/orders/customer/{email}
    @GetMapping("/customer/{email}")
    public ResponseEntity<Map<String, Object>> getOrdersByEmail(@PathVariable String email) {
        List<Order> orders = orderService.getOrdersByEmail(email);
        Map<String, Object> response = new HashMap<>();
        response.put("success", true);
        response.put("count", orders.size());
        response.put("data", orders);
        return ResponseEntity.ok(response);
    }

    // POST create new order
    // POST /api/orders
    // Body: { customerName, customerEmail, productName, quantity, totalPrice, shippingAddress }
    @PostMapping
    public ResponseEntity<Map<String, Object>> createOrder(@RequestBody Order order) {
        Map<String, Object> response = new HashMap<>();
        if (order.getCustomerName() == null || order.getCustomerEmail() == null ||
            order.getProductName()  == null || order.getQuantity()      == null ||
            order.getTotalPrice()   == null || order.getShippingAddress() == null) {
            response.put("success", false);
            response.put("message", "All fields are required: customerName, customerEmail, productName, quantity, totalPrice, shippingAddress");
            return ResponseEntity.badRequest().body(response);
        }
        Order created = orderService.createOrder(order);
        response.put("success", true);
        response.put("message", "Order created successfully");
        response.put("data", created);
        return ResponseEntity.status(HttpStatus.CREATED).body(response);
    }

    // PUT update order
    // PUT /api/orders/{id}
    // Body: any fields to update
    @PutMapping("/{id}")
    public ResponseEntity<Map<String, Object>> updateOrder(@PathVariable Long id, @RequestBody Order order) {
        Map<String, Object> response = new HashMap<>();
        return orderService.updateOrder(id, order)
            .map(updated -> {
                response.put("success", true);
                response.put("message", "Order updated successfully");
                response.put("data", updated);
                return ResponseEntity.ok(response);
            })
            .orElseGet(() -> {
                response.put("success", false);
                response.put("message", "Order not found with id: " + id);
                return ResponseEntity.status(HttpStatus.NOT_FOUND).body(response);
            });
    }

    // DELETE order
    // DELETE /api/orders/{id}
    @DeleteMapping("/{id}")
    public ResponseEntity<Map<String, Object>> deleteOrder(@PathVariable Long id) {
        Map<String, Object> response = new HashMap<>();
        if (orderService.deleteOrder(id)) {
            response.put("success", true);
            response.put("message", "Order deleted successfully");
            return ResponseEntity.ok(response);
        }
        response.put("success", false);
        response.put("message", "Order not found with id: " + id);
        return ResponseEntity.status(HttpStatus.NOT_FOUND).body(response);
    }
}
