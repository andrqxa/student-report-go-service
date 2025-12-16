package com.nam.controller;

import com.nam.exception.TokenRefreshException;
import com.nam.exception.UserException;
import com.nam.model.RefreshToken;
import com.nam.payload.request.LoginRequest;
import com.nam.payload.request.SignupStudentRequest;
import com.nam.payload.request.SignupTeacherRequest;
import com.nam.payload.request.TokenRefreshRequest;
import com.nam.payload.response.ApiResponse;
import com.nam.payload.response.JwtResponse;
import com.nam.payload.response.TokenRefreshResponse;
import com.nam.security.jwt.JwtProvider;
import com.nam.security.services.RefreshTokenService;
import com.nam.security.services.UserDetailsImpl;
import com.nam.service.UserService;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.security.authentication.AuthenticationManager;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import java.util.List;
import java.util.stream.Collectors;

@RestController
@RequestMapping("/auth")
@RequiredArgsConstructor
public class AuthController {

    private final JwtProvider jwtProvider;
    private final UserService userService;
    private final AuthenticationManager authenticationManager;
    private final RefreshTokenService refreshTokenService;

    @PostMapping("/signup/student")
    public ResponseEntity<ApiResponse> createStudent(@RequestBody SignupStudentRequest request) throws UserException {
    
    //write your code

    }

    @PostMapping("/signup/teacher")
    public ResponseEntity<ApiResponse> createTeacher(@RequestBody SignupTeacherRequest request) throws UserException {

    //write your code
    
    }

    @PostMapping("/signin")
    public ResponseEntity<?> authenticateUser(@RequestBody LoginRequest loginRequest) {

    //write your code

    }

    @PostMapping("/refreshtoken")
    public ResponseEntity<?> refreshtoken(@Valid @RequestBody TokenRefreshRequest request) {
            
    //write your code

    }

    @PostMapping("/signout")
    public ResponseEntity<?> logoutUser() {
    
    //write your code

    }

}