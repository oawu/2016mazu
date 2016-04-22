//
//  ClearTempViewController.m
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/3/29.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "ClearTempViewController.h"

@interface ClearTempViewController ()

@end

@implementation ClearTempViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    
    [self.view.layer setBackgroundColor:[UIColor colorWithRed:1 green:1 blue:1 alpha:1].CGColor];
    
    [self.navigationController setNavigationBarHidden:NO];
    self.navigationController.navigationBar.tintColor = [UIColor colorWithRed:1 green:1 blue:1 alpha:1];

    self.scrollView = [UIScrollView new];
    
    [self.scrollView setTranslatesAutoresizingMaskIntoConstraints:NO];
    
    [self.view addSubview:self.scrollView];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeTop multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeLeading relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeLeading multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeTrailing relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeTrailing multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeBottom multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeCenterX relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeCenterX multiplier:1.0 constant:0.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeCenterY multiplier:1.0 constant:0.0]];

    self.list = [NSMutableArray new];
    [self.list addObject:@{
                           @"title": @"清除 GPS .json 檔案！",
                           @"uri": @"gps",
                           @"label": [UILabel new],
                           @"button": [UIButton new],
                           @"result": [UILabel new],
                           @"line": [UILabel new]
                           }];
    [self.list addObject:@{
                           @"title": @"清除 Message .json 檔案！",
                           @"uri": @"messages",
                           @"label": [UILabel new],
                           @"button": [UIButton new],
                           @"result": [UILabel new],
                           @"line": [UILabel new]
                           }];
    [self.list addObject:@{
                           @"title": @"清空 Temp 資料夾！",
                           @"uri": @"temp",
                           @"label": [UILabel new],
                           @"button": [UIButton new],
                           @"result": [UILabel new],
                           @"line": [UILabel new]
                           }];
    
    for (int i = 0; i < [self.list count]; i++) {
        
        UILabel *label = self.list[i][@"label"];
        [label setTranslatesAutoresizingMaskIntoConstraints:NO];
        [label setText:self.list[i][@"title"]];
        [label setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
        [self.scrollView addSubview:label];
        
       if (i == 0)
            [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:label attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeTop multiplier:1 constant:15.0]];
       else [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:label attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.list[i - 1][@"line"] attribute:NSLayoutAttributeBottom multiplier:1 constant:20.0]];

        [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:label attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeLeft multiplier:1 constant:20.0]];
        
        UIButton *button = self.list[i][@"button"];
        [button.layer setBorderColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:1.00].CGColor];
        [button.layer setBorderWidth:1.0f / [UIScreen mainScreen].scale];
        [button.layer setCornerRadius:2];
        [button setClipsToBounds:YES];
        [button setTag:i];
        
        [button setTitle:@"點擊清除" forState:UIControlStateNormal];
        [button setTitle:@"清除中.." forState:UIControlStateDisabled];
        [button setTitleColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:1.00] forState:UIControlStateNormal];
        [button setTitleColor:[UIColor colorWithRed:0.67 green:0.19 blue:0.18 alpha:1.00] forState:UIControlStateHighlighted];
        [button setTitleColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:.60] forState:UIControlStateDisabled];
        [button setTranslatesAutoresizingMaskIntoConstraints:NO];
        [button addTarget:self action:@selector(clean:) forControlEvents:UIControlEventTouchUpInside];

        [self.scrollView addSubview:button];
        [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:button attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:label attribute:NSLayoutAttributeBottom multiplier:1 constant:10]];
        [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:button attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:label attribute:NSLayoutAttributeLeft multiplier:1 constant:0.0]];
        [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:button attribute:NSLayoutAttributeWidth relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:100.0]];

       
        UILabel *result = self.list[i][@"result"];
        [result setTranslatesAutoresizingMaskIntoConstraints:NO];
        [result setTextColor:[UIColor colorWithRed:0.00 green:0.00 blue:0.00 alpha:1.00]];
        [result setText:@"s"];

        [self.scrollView addSubview:result];
        [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:result attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:button attribute:NSLayoutAttributeBottom multiplier:1 constant:-5.0]];
        [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:result attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:button attribute:NSLayoutAttributeRight multiplier:1 constant:20.0]];
        
        UILabel *line = self.list[i][@"line"];
        [line setTranslatesAutoresizingMaskIntoConstraints:NO];
        [line setBackgroundColor:[UIColor colorWithRed:0.70 green:0.70 blue:0.70 alpha:1.00]];
        
        [self.scrollView addSubview:line];
        [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:line attribute:NSLayoutAttributeWidth relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeWidth multiplier:1 constant:0.0]];
        [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:line attribute:NSLayoutAttributeHeight relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:1.0f / [UIScreen mainScreen].scale]];
        [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:line attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:button attribute:NSLayoutAttributeBottom multiplier:1 constant:20]];
        if (i + 1 == [self.list count])[self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:line attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeBottom multiplier:1 constant:-10]];
    }
}

- (void)clean:(UIButton *)sender{
    [sender setEnabled:NO];
    int i = (int)sender.tag;

    AFHTTPRequestOperationManager *httpManager = [AFHTTPRequestOperationManager manager];
    [httpManager.responseSerializer setAcceptableContentTypes:[NSSet setWithObject:@"application/json"]];
    [httpManager GET:[NSString stringWithFormat:@"%@%@", CLEAN_API_URL, self.list[i][@"uri"]]
          parameters:nil
             success:^(AFHTTPRequestOperation *operation, id responseObject) {
                 [sender setEnabled:YES];
                 [self.list[i][@"result"] setText:@"清除完成！"];
             }
             failure:^(AFHTTPRequestOperation *operation, NSError *error) {
                 [sender setEnabled:YES];
                 [self.list[i][@"result"] setText:@"清除失敗！"];
             }
     ];
}
- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
    for (int i = 0; i < [self.list count]; i++) {
        UIButton *button = self.list[i][@"button"];
        [button setEnabled:YES];
        UILabel *result = self.list[i][@"result"];
        [result setText:@""];
    }
}

- (void)viewDidDisappear:(BOOL)animated {
    [super viewDidDisappear:animated];
    for (int i = 0; i < [self.list count]; i++) {
        UIButton *button = self.list[i][@"button"];
        [button setEnabled:YES];
        UILabel *result = self.list[i][@"result"];
        [result setText:@""];
    }
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
