//
//  SystemTableViewController.m
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/3/29.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "SystemTableViewController.h"

@interface SystemTableViewController ()

@end

@implementation SystemTableViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    
//    [self.tableView setSeparatorStyle:UITableViewCellSeparatorStyleNone];
    [self.tableView setBackgroundView:nil];
    [self.tableView setBackgroundColor:[UIColor colorWithRed:0.94 green:0.94 blue:0.96 alpha:1]];
    [self.tableView.layer setBackgroundColor:[UIColor colorWithRed:0.94 green:0.94 blue:0.96 alpha:1].CGColor];

    
    
    
    self.features = [NSMutableArray new];
    [self.features addObject:@{
                          @"name": @"管理系統",
                          @"items": @[
                                  @{
                                      @"name": @"活動選擇",
                                      @"action": @"MarchViewController",
                                      @"image": @"system_00"
                                      },
                                  @{
                                      @"name": @"清除暫存",
                                      @"action": @"ClearTempViewController",
                                      @"image": @"system_01"
                                      },
                                  @{
                                      @"name": @"黑名單列表",
                                      @"action": @"BlackListTableViewController",
                                      @"image": @"system_02"
                                      }
                                  ]
                          }];
    [self.features addObject:@{
                          @"name": @"定位系統",
                          @"items": @[
                                  @{
                                      @"name": @"GPS狀態",
                                      @"action": @"GPSStatusViewController",
                                      @"image": @"system_100"
                                      }]
                          }];
    [self.navigationController pushViewController:[NSClassFromString(@"BlackListTableViewController") new] animated:YES];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

#pragma mark - Table view data source

- (CGFloat)tableView:(UITableView *)tableView heightForHeaderInSection:(NSInteger)section {
    return 35;
}
- (CGFloat)tableView:(UITableView *)tableView heightForFooterInSection:(NSInteger)section {
    return 0.000001f;
}
- (NSString *)tableView:(UITableView *)tableView titleForHeaderInSection:(NSInteger)section {

    return [[self.features objectAtIndex:section] objectForKey:@"name"];
}
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return [self.features count];
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return [[[self.features objectAtIndex:section] objectForKey:@"items"] count];
}


- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:@"cel" forIndexPath:indexPath];

    NSString *text = [[[[self.features objectAtIndex:indexPath.section] objectForKey:@"items"] objectAtIndex:indexPath.row] objectForKey:@"name"];
    NSString *image = [[[[self.features objectAtIndex:indexPath.section] objectForKey:@"items"] objectAtIndex:indexPath.row] objectForKey:@"image"];
    
    [cell.textLabel setText:text];
    [cell.imageView setImage:[UIImage imageNamed:image]];
    [cell setAccessoryType:UITableViewCellAccessoryDisclosureIndicator];

    return cell;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    NSString *action = [[[[self.features objectAtIndex:indexPath.section] objectForKey:@"items"] objectAtIndex:indexPath.row] objectForKey:@"action"];
    
    [self.navigationController pushViewController:[NSClassFromString(action) new] animated:YES];

}


@end
