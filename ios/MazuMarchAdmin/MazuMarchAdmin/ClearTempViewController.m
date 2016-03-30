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
                           @"title": @"清除 All .json 檔案！",
                           @"uri": @"all_jsons",
                           @"label": [UILabel new],
                           @"button": [UIButton new],
                           @"result": [UILabel new],
                           @"line": [UILabel new]
                           }];
    [self.list addObject:@{
                           @"title": @"清除 Path .json 檔案！",
                           @"uri": @"paths",
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
                           @"title": @"清除 Heatmap .json 檔案！",
                           @"uri": @"heatmaps",
                           @"label": [UILabel new],
                           @"button": [UIButton new],
                           @"result": [UILabel new],
                           @"line": [UILabel new]
                           }];
    [self.list addObject:@{
                           @"title": @"清除 Temp .json 檔案！",
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
    
//    NSLog(@"%@", self.list);
//
//    
//    
//    
//    
//    
//    self.labelAllJson = [UILabel new];
//    [self.labelAllJson setTranslatesAutoresizingMaskIntoConstraints:NO];
//    [self.labelAllJson setText:@"清除 All .json 檔案！"];
//    [self.labelAllJson setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
//    
//    [self.scrollView addSubview:self.labelAllJson];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.labelAllJson attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeTop multiplier:1 constant:15.0]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.labelAllJson attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeLeft multiplier:1 constant:20.0]];
//
//    self.buttonAllJson = [UIButton new];
//    
//    [self.buttonAllJson.layer setBorderColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:1.00].CGColor];
//    [self.buttonAllJson.layer setBorderWidth:1.0f / [UIScreen mainScreen].scale];
//    [self.buttonAllJson.layer setCornerRadius:2];
//    [self.buttonAllJson setClipsToBounds:YES];
//    
//    [self.buttonAllJson setTitle:@"點擊清除" forState:UIControlStateNormal];
//    [self.buttonAllJson setTitle:@"清除中.." forState:UIControlStateDisabled];
//    [self.buttonAllJson setTitleColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:1.00] forState:UIControlStateNormal];
//    [self.buttonAllJson setTitleColor:[UIColor colorWithRed:0.67 green:0.19 blue:0.18 alpha:1.00] forState:UIControlStateHighlighted];
//    [self.buttonAllJson setTitleColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:.60] forState:UIControlStateDisabled];
//    [self.buttonAllJson setTranslatesAutoresizingMaskIntoConstraints:NO];
//    [self.buttonAllJson addTarget:self action:@selector(cleanAllJson:) forControlEvents:UIControlEventTouchUpInside];
//    
//    [self.scrollView addSubview:self.buttonAllJson];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.buttonAllJson attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.labelAllJson attribute:NSLayoutAttributeBottom multiplier:1 constant:10]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.buttonAllJson attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.labelAllJson attribute:NSLayoutAttributeLeft multiplier:1 constant:0.0]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.buttonAllJson attribute:NSLayoutAttributeWidth relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:100.0]];
//
//    self.resultAllJson = [UILabel new];
//    [self.resultAllJson setTranslatesAutoresizingMaskIntoConstraints:NO];
//    [self.resultAllJson setTextColor:[UIColor colorWithRed:0.00 green:0.00 blue:0.00 alpha:1.00]];
//    [self.resultAllJson setText:@""];
//    
//    [self.scrollView addSubview:self.resultAllJson];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.resultAllJson attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.buttonAllJson attribute:NSLayoutAttributeBottom multiplier:1 constant:-5.0]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.resultAllJson attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.buttonAllJson attribute:NSLayoutAttributeRight multiplier:1 constant:20.0]];
//    
//    UILabel *line = [UILabel new];
//    [line setTranslatesAutoresizingMaskIntoConstraints:NO];
//    [line setBackgroundColor:[UIColor colorWithRed:0.70 green:0.70 blue:0.70 alpha:1.00]];
//
//    [self.scrollView addSubview:line];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:line attribute:NSLayoutAttributeWidth relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeWidth multiplier:1 constant:0.0]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:line attribute:NSLayoutAttributeHeight relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:1.0f / [UIScreen mainScreen].scale]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:line attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.buttonAllJson attribute:NSLayoutAttributeBottom multiplier:1 constant:20]];
//
//    
//    self.labelPathJson = [UILabel new];
//    [self.labelPathJson setTranslatesAutoresizingMaskIntoConstraints:NO];
//    [self.labelPathJson setText:@"清除 Path .json 檔案！"];
//    [self.labelPathJson setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
//    
//    [self.scrollView addSubview:self.labelPathJson];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.labelPathJson attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:line attribute:NSLayoutAttributeBottom multiplier:1 constant:15.0]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.labelPathJson attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.labelAllJson attribute:NSLayoutAttributeLeft multiplier:1 constant:0.0]];
//    
//    
//    self.buttonPathJson = [UIButton new];
//    
//    [self.buttonPathJson.layer setBorderColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:1.00].CGColor];
//    [self.buttonPathJson.layer setBorderWidth:1.0f / [UIScreen mainScreen].scale];
//    [self.buttonPathJson.layer setCornerRadius:2];
//    [self.buttonPathJson setClipsToBounds:YES];
//    
//    [self.buttonPathJson setTitle:@"點擊清除" forState:UIControlStateNormal];
//    [self.buttonPathJson setTitle:@"清除中.." forState:UIControlStateDisabled];
//    [self.buttonPathJson setTitleColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:1.00] forState:UIControlStateNormal];
//    [self.buttonPathJson setTitleColor:[UIColor colorWithRed:0.67 green:0.19 blue:0.18 alpha:1.00] forState:UIControlStateHighlighted];
//    [self.buttonPathJson setTitleColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:.60] forState:UIControlStateDisabled];
//    [self.buttonPathJson setTranslatesAutoresizingMaskIntoConstraints:NO];
//    [self.buttonPathJson addTarget:self action:@selector(cleanPathJson:) forControlEvents:UIControlEventTouchUpInside];
//    
//    [self.scrollView addSubview:self.buttonPathJson];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.buttonPathJson attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.labelPathJson attribute:NSLayoutAttributeBottom multiplier:1 constant:10]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.buttonPathJson attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.labelPathJson attribute:NSLayoutAttributeLeft multiplier:1 constant:0.0]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.buttonPathJson attribute:NSLayoutAttributeWidth relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:100.0]];
//
//    UILabel *line2 = [UILabel new];
//    [line2 setTranslatesAutoresizingMaskIntoConstraints:NO];
//    [line2 setBackgroundColor:[UIColor colorWithRed:0.70 green:0.70 blue:0.70 alpha:1.00]];
//    
//    [self.scrollView addSubview:line2];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:line2 attribute:NSLayoutAttributeWidth relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeWidth multiplier:1 constant:0.0]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:line2 attribute:NSLayoutAttributeHeight relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:1.0f / [UIScreen mainScreen].scale]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:line2 attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.buttonPathJson attribute:NSLayoutAttributeBottom multiplier:1 constant:20]];
//    
//    self.labelMessageJson = [UILabel new];
//    [self.labelMessageJson setTranslatesAutoresizingMaskIntoConstraints:NO];
//    [self.labelMessageJson setText:@"清除 Message .json 檔案！"];
//    [self.labelMessageJson setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
//    
//    [self.scrollView addSubview:self.labelMessageJson];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.labelMessageJson attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:line2 attribute:NSLayoutAttributeBottom multiplier:1 constant:15.0]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.labelMessageJson attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.labelPathJson attribute:NSLayoutAttributeLeft multiplier:1 constant:0.0]];
//    
//    
//    self.buttonMessageJson = [UIButton new];
//    
//    [self.buttonMessageJson.layer setBorderColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:1.00].CGColor];
//    [self.buttonMessageJson.layer setBorderWidth:1.0f / [UIScreen mainScreen].scale];
//    [self.buttonMessageJson.layer setCornerRadius:2];
//    [self.buttonMessageJson setClipsToBounds:YES];
//    
//    [self.buttonMessageJson setTitle:@"點擊清除" forState:UIControlStateNormal];
//    [self.buttonMessageJson setTitle:@"清除中.." forState:UIControlStateDisabled];
//    [self.buttonMessageJson setTitleColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:1.00] forState:UIControlStateNormal];
//    [self.buttonMessageJson setTitleColor:[UIColor colorWithRed:0.67 green:0.19 blue:0.18 alpha:1.00] forState:UIControlStateHighlighted];
//    [self.buttonMessageJson setTitleColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:.60] forState:UIControlStateDisabled];
//    [self.buttonMessageJson setTranslatesAutoresizingMaskIntoConstraints:NO];
//    [self.buttonMessageJson addTarget:self action:@selector(cleanMessageJson:) forControlEvents:UIControlEventTouchUpInside];
//    
//    [self.scrollView addSubview:self.buttonMessageJson];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.buttonMessageJson attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.labelMessageJson attribute:NSLayoutAttributeBottom multiplier:1 constant:10]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.buttonMessageJson attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.labelMessageJson attribute:NSLayoutAttributeLeft multiplier:1 constant:0.0]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.buttonMessageJson attribute:NSLayoutAttributeWidth relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:100.0]];
//    
//    self.resultMessageJson = [UILabel new];
//    [self.resultMessageJson setTranslatesAutoresizingMaskIntoConstraints:NO];
//    [self.resultMessageJson setTextColor:[UIColor colorWithRed:0.00 green:0.00 blue:0.00 alpha:1.00]];
//    [self.resultMessageJson setText:@""];
//    
//    [self.scrollView addSubview:self.resultMessageJson];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.resultMessageJson attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.buttonMessageJson attribute:NSLayoutAttributeBottom multiplier:1 constant:-5.0]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.resultMessageJson attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.buttonMessageJson attribute:NSLayoutAttributeRight multiplier:1 constant:20.0]];
//    
//    
//    UILabel *line3 = [UILabel new];
//    [line3 setTranslatesAutoresizingMaskIntoConstraints:NO];
//    [line3 setBackgroundColor:[UIColor colorWithRed:0.70 green:0.70 blue:0.70 alpha:1.00]];
//    
//    [self.scrollView addSubview:line3];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:line3 attribute:NSLayoutAttributeWidth relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeWidth multiplier:1 constant:0.0]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:line3 attribute:NSLayoutAttributeHeight relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:1.0f / [UIScreen mainScreen].scale]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:line3 attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.buttonMessageJson attribute:NSLayoutAttributeBottom multiplier:1 constant:20]];
//    
//    
//    self.labelHeatmapJson = [UILabel new];
//    [self.labelHeatmapJson setTranslatesAutoresizingMaskIntoConstraints:NO];
//    [self.labelHeatmapJson setText:@"清除 Heatmap .json 檔案！"];
//    [self.labelHeatmapJson setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
//    
//    [self.scrollView addSubview:self.labelHeatmapJson];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.labelHeatmapJson attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:line3 attribute:NSLayoutAttributeBottom multiplier:1 constant:15.0]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.labelHeatmapJson attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.labelMessageJson attribute:NSLayoutAttributeLeft multiplier:1 constant:0.0]];
//    
//    
//    self.buttonHeatmapJson = [UIButton new];
//    
//    [self.buttonHeatmapJson.layer setBorderColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:1.00].CGColor];
//    [self.buttonHeatmapJson.layer setBorderWidth:1.0f / [UIScreen mainScreen].scale];
//    [self.buttonHeatmapJson.layer setCornerRadius:2];
//    [self.buttonHeatmapJson setClipsToBounds:YES];
//    
//    [self.buttonHeatmapJson setTitle:@"點擊清除" forState:UIControlStateNormal];
//    [self.buttonHeatmapJson setTitle:@"清除中.." forState:UIControlStateDisabled];
//    [self.buttonHeatmapJson setTitleColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:1.00] forState:UIControlStateNormal];
//    [self.buttonHeatmapJson setTitleColor:[UIColor colorWithRed:0.67 green:0.19 blue:0.18 alpha:1.00] forState:UIControlStateHighlighted];
//    [self.buttonHeatmapJson setTitleColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:.60] forState:UIControlStateDisabled];
//    [self.buttonHeatmapJson setTranslatesAutoresizingMaskIntoConstraints:NO];
//    [self.buttonHeatmapJson addTarget:self action:@selector(cleanHeatmapJson:) forControlEvents:UIControlEventTouchUpInside];
//    
//    [self.scrollView addSubview:self.buttonHeatmapJson];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.buttonHeatmapJson attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.labelHeatmapJson attribute:NSLayoutAttributeBottom multiplier:1 constant:10]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.buttonHeatmapJson attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.labelHeatmapJson attribute:NSLayoutAttributeLeft multiplier:1 constant:0.0]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.buttonHeatmapJson attribute:NSLayoutAttributeWidth relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:100.0]];
//    
//    self.resultHeatmapJson = [UILabel new];
//    [self.resultHeatmapJson setTranslatesAutoresizingMaskIntoConstraints:NO];
//    [self.resultHeatmapJson setTextColor:[UIColor colorWithRed:0.00 green:0.00 blue:0.00 alpha:1.00]];
//    [self.resultHeatmapJson setText:@""];
//    
//    [self.scrollView addSubview:self.resultHeatmapJson];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.resultHeatmapJson attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.buttonHeatmapJson attribute:NSLayoutAttributeBottom multiplier:1 constant:-5.0]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.resultHeatmapJson attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.buttonHeatmapJson attribute:NSLayoutAttributeRight multiplier:1 constant:20.0]];
//    
//    
//    
//    UILabel *line4 = [UILabel new];
//    [line4 setTranslatesAutoresizingMaskIntoConstraints:NO];
//    [line4 setBackgroundColor:[UIColor colorWithRed:0.70 green:0.70 blue:0.70 alpha:1.00]];
//    
//    [self.scrollView addSubview:line4];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:line4 attribute:NSLayoutAttributeWidth relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeWidth multiplier:1 constant:0.0]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:line4 attribute:NSLayoutAttributeHeight relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:1.0f / [UIScreen mainScreen].scale]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:line4 attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.buttonHeatmapJson attribute:NSLayoutAttributeBottom multiplier:1 constant:20]];
//    
//    self.labelTemp = [UILabel new];
//    [self.labelTemp setTranslatesAutoresizingMaskIntoConstraints:NO];
//    [self.labelTemp setText:@"清除 Temp 內所有檔案！"];
//    [self.labelTemp setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
//    
//    [self.scrollView addSubview:self.labelTemp];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.labelTemp attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:line4 attribute:NSLayoutAttributeBottom multiplier:1 constant:15.0]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.labelTemp attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.labelHeatmapJson attribute:NSLayoutAttributeLeft multiplier:1 constant:0.0]];
//    
//    
//    self.buttonTemp = [UIButton new];
//    
//    [self.buttonTemp.layer setBorderColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:1.00].CGColor];
//    [self.buttonTemp.layer setBorderWidth:1.0f / [UIScreen mainScreen].scale];
//    [self.buttonTemp.layer setCornerRadius:2];
//    [self.buttonTemp setClipsToBounds:YES];
//    
//    [self.buttonTemp setTitle:@"點擊清除" forState:UIControlStateNormal];
//    [self.buttonTemp setTitle:@"清除中.." forState:UIControlStateDisabled];
//    [self.buttonTemp setTitleColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:1.00] forState:UIControlStateNormal];
//    [self.buttonTemp setTitleColor:[UIColor colorWithRed:0.67 green:0.19 blue:0.18 alpha:1.00] forState:UIControlStateHighlighted];
//    [self.buttonTemp setTitleColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:.60] forState:UIControlStateDisabled];
//    [self.buttonTemp setTranslatesAutoresizingMaskIntoConstraints:NO];
//    [self.buttonTemp addTarget:self action:@selector(cleanTemp:) forControlEvents:UIControlEventTouchUpInside];
//    
//    [self.scrollView addSubview:self.buttonTemp];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.buttonTemp attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.labelTemp attribute:NSLayoutAttributeBottom multiplier:1 constant:10]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.buttonTemp attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.labelTemp attribute:NSLayoutAttributeLeft multiplier:1 constant:0.0]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.buttonTemp attribute:NSLayoutAttributeWidth relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:100.0]];
//    
//    self.resultTemp = [UILabel new];
//    [self.resultTemp setTranslatesAutoresizingMaskIntoConstraints:NO];
//    [self.resultTemp setTextColor:[UIColor colorWithRed:0.00 green:0.00 blue:0.00 alpha:1.00]];
//    [self.resultTemp setText:@""];
//    
//    [self.scrollView addSubview:self.resultTemp];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.resultTemp attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.buttonTemp attribute:NSLayoutAttributeBottom multiplier:1 constant:-5.0]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.resultTemp attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.buttonTemp attribute:NSLayoutAttributeRight multiplier:1 constant:20.0]];
//    
//    
//    UILabel *line5 = [UILabel new];
//    [line5 setTranslatesAutoresizingMaskIntoConstraints:NO];
//    [line5 setBackgroundColor:[UIColor colorWithRed:0.70 green:0.70 blue:0.70 alpha:1.00]];
//    
//    [self.scrollView addSubview:line5];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:line5 attribute:NSLayoutAttributeWidth relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeWidth multiplier:1 constant:0.0]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:line5 attribute:NSLayoutAttributeHeight relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:1.0f / [UIScreen mainScreen].scale]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:line5 attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.buttonTemp attribute:NSLayoutAttributeBottom multiplier:1 constant:20]];
//    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:line5 attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeBottom multiplier:1 constant:-10]];
    
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

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
