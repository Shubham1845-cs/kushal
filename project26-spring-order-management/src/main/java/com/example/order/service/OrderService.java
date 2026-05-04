package com.example.order.service;

import com.example.order.model.Order;
import com.example.order.repository.OrderRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import java.util.List;
import java.util.Optional;

@Service
public class OrderService {

    @Autowired
    private OrderRepository orderRepository;

    // Get all orders
    public List<Order> getAllOrders() {
        return orderRepository.findAll();
    }

    // Get order by ID
    public Optional<Order> getOrderById(Long id) {
        return orderRepository.findById(id);
    }

    // Get orders by status
    public List<Order> getOrdersByStatus(String status) {
        return orderRepository.findByStatus(status.toUpperCase());
    }

    // Get orders by customer email
    public List<Order> getOrdersByEmail(String email) {
        return orderRepository.findByCustomerEmail(email);
    }

    // Create new order
    public Order createOrder(Order order) {
        return orderRepository.save(order);
    }

    // Update order
    public Optional<Order> updateOrder(Long id, Order updatedOrder) {
        return orderRepository.findById(id).map(existing -> {
            if (updatedOrder.getCustomerName()    != null) existing.setCustomerName(updatedOrder.getCustomerName());
            if (updatedOrder.getCustomerEmail()   != null) existing.setCustomerEmail(updatedOrder.getCustomerEmail());
            if (updatedOrder.getProductName()     != null) existing.setProductName(updatedOrder.getProductName());
            if (updatedOrder.getQuantity()        != null) existing.setQuantity(updatedOrder.getQuantity());
            if (updatedOrder.getTotalPrice()      != null) existing.setTotalPrice(updatedOrder.getTotalPrice());
            if (updatedOrder.getStatus()          != null) existing.setStatus(updatedOrder.getStatus().toUpperCase());
            if (updatedOrder.getShippingAddress() != null) existing.setShippingAddress(updatedOrder.getShippingAddress());
            return orderRepository.save(existing);
        });
    }

    // Delete order
    public boolean deleteOrder(Long id) {
        if (orderRepository.existsById(id)) {
            orderRepository.deleteById(id);
            return true;
        }
        return false;
    }
}
