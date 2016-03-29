//
//  MyTabBarController.m
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/3/29.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "MyTabBarController.h"

@interface MyTabBarController ()

@end

@implementation MyTabBarController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    
//    self.tabBar.items
    for (int i = 0; i < [self.tabBar.items count]; i++) {
        UITabBarItem * tabItem = [self.tabBar.items objectAtIndex:i];
        [tabItem setImageInsets:UIEdgeInsetsMake(0, 0, 0, 0)];
        [tabItem setImage:[[UIImage imageNamed:[NSString stringWithFormat: @"bar_item_%02i", i]] imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal]];
        [tabItem setSelectedImage:[[UIImage imageNamed:[NSString stringWithFormat: @"bar_item_%02i_a", i]] imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal]];
        [tabItem setTitleTextAttributes:@{ NSForegroundColorAttributeName : [UIColor colorWithRed:0.68 green:0.68 blue:0.68 alpha:1.00] } forState:UIControlStateNormal];
        [tabItem setTitleTextAttributes:@{ NSForegroundColorAttributeName : [UIColor colorWithRed:0.67 green:0.19 blue:0.18 alpha:1.00] } forState:UIControlStateSelected];
    }
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
